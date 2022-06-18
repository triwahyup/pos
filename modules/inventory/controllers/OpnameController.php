<?php

namespace app\modules\inventory\controllers;

use app\models\Logs;
use app\models\LogsMail;
use app\models\User;
use app\modules\master\models\Profile;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use app\modules\inventory\models\InventoryOpname;
use app\modules\inventory\models\InventoryOpnameApproval;
use app\modules\inventory\models\InventoryOpnameDetail;
use app\modules\inventory\models\InventoryOpnameSearch;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\inventory\models\TempInventoryOpnameDetail;
use app\modules\pengaturan\models\PengaturanApproval;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * OpnameController implements the CRUD actions for InventoryOpname model.
 */
class OpnameController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['create', 'create-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-item', 'search', 'autocomplete', 'item', 'popup', 'temp', 'get-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'post', 'update-temp', 'send-approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[U]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[D]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[A]')),
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all InventoryOpname models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventoryOpnameSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InventoryOpname model.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($code)
    {
        $model = $this->findModel($code);
        $typeuser = \Yii::$app->user->identity->profile->typeUser->value;
        $sendApproval = false;
        $post = false;
        if($typeuser == 'ADMINISTRATOR' || $typeuser == 'ADMIN'){
            if($model->status_approval == 0 || $model->status_approval == 3){
                $sendApproval = true;
            }
            if($model->status_approval == 2 && ($model->post == 0 || empty($model->post))){
                $post = true;
            }
            
        }
        $typeApproval = false;
        $approval = InventoryOpnameApproval::findOne(['code'=>$code, 'status'=>2]);
        if(isset($approval)){
            if(($model->status_approval==1) && ($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                $typeApproval = true;
            }
        }
        return $this->render('view', [
            'model' => $model,
            'sendApproval' => $sendApproval,
            'post' => $post,
            'typeApproval' => $typeApproval,
            'typeuser' => $typeuser,
        ]);
    }

    /**
     * Creates a new InventoryOpname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $supplier = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_SUPPLIER'], 'status' => 1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = new InventoryOpname();
        $temp = new TempInventoryOpnameDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->code = $model->generateCode();
                    $model->date = date('Y-m-d');
                    $model->user_id = \Yii::$app->user->id;
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new InventoryOpnameDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->code = $model->code;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE OPNAME DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE OPNAME: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE OPNAME: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                    
                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS CREATE OPNAME.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'code' => $model->code]);
                    }else{
                        $transaction->rollBack();
                    }
                }catch(\Exception $e){
                    $success = false;
                    $message = $e->getMessage();
				    $transaction->rollBack();
                }
                $logs =	[
                    'type' => Logs::TYPE_USER,
                    'description' => $message,
                ];
                Logs::addLog($logs);
                \Yii::$app->session->setFlash('error', $message);
            }
        } else {
            $model->loadDefaultValues();
            $this->emptyTemp();
        }

        return $this->render('create', [
            'model' => $model,
            'temp' => $temp,
            'supplier' => $supplier,
        ]);
    }

    /**
     * Updates an existing InventoryOpname model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $supplier = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_SUPPLIER'], 'status' => 1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = $this->findModel($code);
        $temp = new TempInventoryOpnameDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->status_approval = null;
                    if($model->save()){
                        if(count($model->temps) > 0){
                            foreach($model->details as $empty)
                                $empty->delete();
                            foreach($model->temps as $temp){
                                $detail = new InventoryOpnameDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE OPNAME DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE OPNAME: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE OPNAME: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS UPDATE OPNAME.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'code' => $model->code]);
                    }else{
                        $transaction->rollBack();
                    }
                }catch(\Exception $e){
                    $success = false;
                    $message = $e->getMessage();
                    $transaction->rollBack();
                }
                $logs =	[
                    'type' => Logs::TYPE_USER,
                    'description' => $message,
                ];
                Logs::addLog($logs);
                \Yii::$app->session->setFlash('error', $message);
            }
        }else{
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
                return $this->redirect(['index']);
            }else{
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Post ke Stock Gudang.');
                    return $this->redirect(['index']);
                }else{
                    $this->emptyTemp();
                    foreach($model->details as $detail){
                        $temp = new TempInventoryOpnameDetail();
                        $temp->attributes = $detail->attributes;
                        $temp->user_id = \Yii::$app->user->id;
                        if(!$temp->save()){
                            $message = (count($temp->errors) > 0) ? 'ERROR LOAD OPNAME DETAIL: ' : '';
                            foreach($temp->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                            \Yii::$app->session->setFlash('error', $message);
                        }
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'temp' => $temp,
            'supplier' => $supplier,
        ]);
    }

    /**
     * Deletes an existing InventoryOpname model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($code)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($code);
        if(isset($model)){
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
            }else{
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Post ke Stock Gudang.');
                }else{
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();
                    try{
                        $model->status = 0;
                        if($model->save()){
                            foreach($model->details as $detail){
                                $detail->status = 0;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL OPNAME: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR DELETE OPNAME: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
        
                        if($success){
                            $transaction->commit();
                            $message = '['.$model->code.'] SUCCESS DELETE OPNAME.';
                            \Yii::$app->session->setFlash('success', $message);
                        }else{
                            $transaction->rollBack();
                            \Yii::$app->session->setFlash('error', $message);
                        }
                    }catch(\Exception $e){
                        $success = false;
                        $message = $e->getMessage();
                        $transaction->rollBack();
                        \Yii::$app->session->setFlash('error', $message);
                    }
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the InventoryOpname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return InventoryOpname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = InventoryOpname::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListItem()
    {
        $model = MasterMaterial::find()->where(['status'=>1])->orderBy(['code'=>SORT_ASC])->limit(10)->all();
        return json_encode(['data'=>$this->renderPartial('_list_item', ['model'=>$model])]);
    }

    public function actionAutocomplete()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = MasterMaterial::find()
                ->select(['code', 'concat(code,"-",name) label', 'concat(code,"-",name) name'])
                ->where(['status'=>1])
                ->andWhere('concat(code,"-",name) LIKE "%'.$_POST['search'].'%"')
                ->asArray()
                ->limit(10)
                ->all();
        }
        return  json_encode($model);
    }

    public function actionSearch()
    {
        $model = [];
        if(isset($_POST['code'])){
            $model = MasterMaterial::find()->where(['code'=>$_POST['code'], 'status'=>1])->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_item', ['model'=>$model])]);
    }
    
    public function actionItem()
    {
        $model = MasterMaterial::find()
            ->alias('a')
            ->select(['b.*', 'b.code as satuan_code', 'a.code as item_code', 'a.name as item_name'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
            ->asArray()
            ->one();
        $stock = 0;
        if(empty($model)){
            $model = [];
        }else{
            $stockItem = InventoryStockItem::findOne(['item_code'=>$model['item_code'], 'supplier_code'=>$_POST['supplier']]);
            if(isset($stockItem)){
                $konversi = $stockItem->nKonversi($model['item_code'], $stockItem['onhand']);
            }else{
                $konversi = [0, 0];
            }
            $stock = $konversi;
        }
        return json_encode(['item'=>$model, 'stock'=>$stock]);
    }

    public function actionTemp()
    {
        $temps = TempInventoryOpnameDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        return json_encode(['model' => $this->renderAjax('_temp', ['temps'=>$temps])]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempInventoryOpnameDetail::find()
            ->alias('a')
            ->select(['a.*', 'b.name as item_name'])
            ->leftJoin('master_material b', 'b.code = a.item_code')
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $dataTemp = $request->post('TempInventoryOpnameDetail');
            $ctemp = TempInventoryOpnameDetail::findOne(['item_code'=>$dataTemp['item_code'], 'user_id'=>\Yii::$app->user->id]);
            if(empty($ctemp)){
                $dataHeader = $request->post('InventoryOpname');
                if($dataTemp['qty_1'] > 0 || $dataTemp['qty_2'] > 0){
                    $temp = new TempInventoryOpnameDetail();
                    $temp->attributes = (array)$dataTemp;
                    $temp->attributes = ($temp->item) ? $temp->item->attributes : '';
                    $temp->attributes = ($temp->item->satuan) ? $temp->item->satuan->attributes : '';
                    $temp->code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
                    $temp->supplier_code = $dataHeader['supplier_code'];
                    $temp->urutan = $temp->count +1;
                    $temp->user_id = \Yii::$app->user->id;
                    
                    $stock = (isset($temp->stock)) ? $temp->stock : (new InventoryStockItem());
                    $opname = $stock->satuanTerkecil($dataTemp['item_code'], [
                        0=>$dataTemp['qty_1'],
                        1=>$dataTemp['qty_2']]);
                    $temp->selisih = $stock->onhand - $opname;
                    $temp->keterangan = $dataTemp['keterangan'];
                    if($stock->onhand != $opname){
                        if($stock->onhand > $opname){
                            $temp->balance = 0; // MINUS
                        }else{
                            $temp->balance = 2; // LEBIH
                        }
                    }else{
                        $temp->balance = 1; // BALANCE
                    }
                    if(!$temp->save()){
                        $success = false;
                        foreach($temp->errors as $error => $value){
                            $message = $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = 'QTY Opname wajib diisi.';
                }
            }else{
                $success = false;
                $message = 'Item sudah di inputkan.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionUpdateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'UPDATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $dataTemp = $request->post('TempInventoryOpnameDetail');
            $dataHeader = $request->post('InventoryOpname');
            if($dataTemp['qty_1'] > 0 || $dataTemp['qty_2'] > 0){
                $temp = $this->findTemp($dataTemp['id']);
                $temp->attributes = (array)$dataTemp;
                $temp->attributes = ($temp->item) ? $temp->item->attributes : '';
                $temp->attributes = ($temp->item->satuan) ? $temp->item->satuan->attributes : '';
                $temp->code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
                $temp->user_id = \Yii::$app->user->id;

                $stock = (isset($temp->stock)) ? $temp->stock : (new InventoryStockItem());
                $opname = $stock->satuanTerkecil($dataTemp['item_code'], [
                    0=>$dataTemp['qty_1'],
                    1=>$dataTemp['qty_2']]);
                $temp->selisih = $stock->onhand - $opname;
                $temp->keterangan = $dataTemp['keterangan'];
                if($stock->onhand != $opname){
                    if($stock->onhand > $opname){
                        $temp->balance = 0; // MINUS
                    }else{
                        $temp->balance = 2; // LEBIH
                    }
                }else{
                    $temp->balance = 1; // BALANCE
                }
                if(!$temp->save()){
                    $success = false;
                    foreach($temp->errors as $error => $value){
                        $message = $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
            }else{
                $success = false;
                $message = 'QTY Opname wajib diisi.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteTemp($id)
    {
        $success = true;
        $message = 'DELETE TEMP SUCCESSFULLY';
        $temp = $this->findTemp($id);
        if(isset($temp)){
            if($temp->delete()){
                foreach($temp->tmps as $index=>$val){
                    $val->urutan = $index +1;
                    if(!$val->save()){
                        $success = false;
                        foreach($val->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }
            }else{
                $success = false;
                foreach($temp->errors as $error => $value){
                    $message = $value[0].', ';
                }
                $message = substr($message, 0, -2);
            }
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    protected function findTemp($id)
    {
        $temp = TempInventoryOpnameDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempInventoryOpnameDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempInventoryOpnameDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_inventory_opname_detail AUTO_INCREMENT=1')->query();
        }
    }

    public function actionSendApproval($code)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($code);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->status_approval = 1;
                if($model->save()){
                    $approvals = (new PengaturanApproval)->approval('stock-opname');
                    if(isset($approvals)){
                        $approvaln = InventoryOpnameApproval::findAll(['code'=>$code]);
                        if(count($approvaln) > 0)
                        InventoryOpnameApproval::deleteAll('code=:code', [':code'=>$code]);
                        foreach($approvals as $approval){
                            $app = new InventoryOpnameApproval();
                            $app->attributes = $approval->attributes;
                            $app->code = $code;
                            $app->status = 1;
                            if(!$app->save()){
                                $success = false;
                                foreach($app->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = 'Setting approval Opname belum ada. Silakan hubungi administrator utk melakukan setting approval.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS OPNAME: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $mailapproval = json_decode($this->mailapproval($model->code));
                    if($mailapproval->success){
                        $message = '['.$model->code.'] SUCCESS SEND APPROVAL OPNAME.';
                        $transaction->commit();
                        \Yii::$app->session->setFlash('success', $message);
                    }else{
                        $success = false;
                        $message = $mailapproval->message;
                    }
                }else{
                    $transaction->rollBack();
                }
            }catch(Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }else{
            $success = false;
            $message = 'Data Opname not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'code' => $model->code]);
    }

    public function actionApproval()
    {
        $request = \Yii::$app->request;
        $data = $request->post('InventoryOpnameApproval');
        $success = true;
		$message = '';
        $model = $this->findModel($data['code']);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $approval = InventoryOpnameApproval::findOne(['code'=>$model->code, 'status'=>2]);
                if(isset($approval)){
                    if(($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                        // APPROVE
                        if($data['type'] == 'APPROVE'){
                            $approval->comment = $data['comment'];
							$approval->status=3;
							if(empty($approval->user_id)){
								$approval->user_id = \Yii::$app->user->id;
							}
                            if($approval->save()){
                                $mailapproval = json_decode($this->mailapproval($model->code));
                                if($mailapproval->success){
                                    $is_akhir = true;
                                    if($mailapproval->akhir){
                                        $mailakhir = json_decode($this->mailapproval_akhir($model->code, $approval->comment));
                                        if($mailakhir->success){
                                            $model->status_approval=2;
                                            if(!$model->save()){
												$success = false;
												foreach($model->errors as $error => $value)
													$message .= strtoupper($value[0].', ');
												$message = substr($message, 0, -2);
											}
                                        }else{
                                            $is_akhir = false;
											$message = $mailakhir->message;
                                        }
                                    }
                                    if($is_akhir){
                                        $transaction->commit();
                                        $message = '['.$model->code.'] SUCCESS APPROVE OPNAME.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'code' => $model->code]);
                                    }else{
                                        $transaction->rollBack();
										$message = $mailapproval->message;
                                    }
                                }
                            }else{
                                $success = false;
								$message = (count($approval->errors) > 0) ? 'ERROR CREATE APPROVAL: ' : '';
								foreach($approval->errors as $error => $value){
									$message .= strtoupper($value[0].', ');
								}
								$message = substr($message, 0, -2);
                            }
                        }
                        // REJECTED
                        if($data['type'] == "REJECT"){
                            if(!empty($data['comment'])){
                                $approval->comment = $data['comment'];
								$approval->status=4;
								if(empty($approval->user_id)){
									$approval->user_id = \Yii::$app->user->id;
								}
                                if(!$approval->save()){
									$success = false;
									foreach($approval->errors as $error => $value)
										$message .= strtoupper($value[0].', ');
									$message = substr($message, 0, -2);
								}
                                $model->status_approval=3;
                                if(!$model->save()){
									$success = false;
									foreach($model->errors as $error => $value)
										$message .= strtoupper($value[0].', ');
									$message = substr($message, 0, -2);
								}

                                if($success){
                                    $mailakhir = json_decode($this->mailapproval_akhir($model->code, $approval->comment));
                                    if($mailakhir->success){
                                        $transaction->commit();
                                        $message = '['.$model->code.'] SUCCESS REJECT OPNAME.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'code' => $model->code]);
                                    }else{
                                        $success = false;
										$message .= $mailakhir->message;
                                    }
                                }else{
                                    $transaction->rollBack();
                                }
                            }else{
                                $success = false;
								$message = 'PLEASE, INSERT YOUR COMMENT.';
                            }
                        }
                    }else{
                        $success = false;
				        $message = 'YOU NOT ALLOWED TO APPROVE DOCUMENT.';
                    }
                }else{
                    $success = false;
					$message = 'APPROVAL NOT FOUND.';
                }
            }catch(Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }else{
            $success = false;
            $message = 'Data Opname not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'code' => $model->code]);
    }

    public function actionPopup()
    {
        $request = \Yii::$app->request;
        $approval = InventoryOpnameApproval::findOne(['code'=>$request->post('code'), 'status'=>2]);
        $model = $this->findModel($request->post('code'));
        if($request->post('type') == 'APPROVE'){
            return $this->renderPartial('_popup_approve', [
                'model' => $model,
                'approval' => $approval,
                'title' => 'Form Approve',
            ]);
        }
        if($request->post('type') == 'REJECT'){
            return $this->renderPartial('_popup_reject', [
                'model' => $model,
                'approval' => $approval,
                'title' => 'Form Reject',
            ]);
        }
    }

    function mailapproval($code)
    {
        $success = true;
		$message = '';
        $akhir = false;
		$urutan = 0;
		$profile = [];
        $approvals = InventoryOpnameApproval::find()->where(['code'=>$code])->orderBy(['urutan'=>SORT_ASC])->all();
        if(count($approvals) > 0){
            foreach($approvals as $approval){
                if(!$urutan){
					$profile = $approval->profile; // Function to call Profile User
				}
                if($approval->status == 1 && !$urutan && $success){
                    $urutan = $approval->urutan;
                }else if(($approval->status == 2 || $approval->status == 4) && (!$urutan)){
                    $success = false;
					$name = 'ANONIM';
                    if(!empty($approval->user_id)){
                        $name = $approval->profile->name;
                    }else{
                        $name = 'User Type: '. $approval->typeUser->value;
                    }

                    if($approval->status == 2){
						$message = 'Status Email is WAITING APPROVE for '. $name;
					}else{
						$message = 'Status Email is REJECT for '. $name;
					}
                }
            }
            if($urutan){
                $app = InventoryOpnameApproval::findOne(['code'=>$code, 'urutan'=>$urutan]);
                if(isset($app)){
                    $name = '';
                    if(!empty($app->user_id)){
						$name = 'Yth. Bpk/Ibu '. $app->profile[0]->name;
					}else{
						$name = 'Yth. Bpk/Ibu Divisi <b>'. $app->typeUser->value .'</b>';
					}

                    $emailuser = [];
					$str_mail = '';
                    foreach($profile as $user){
						if(!empty($user->email)){
							$emailuser[] = strtolower($user->email);
							$str_mail .= strtolower($user->email).', ';
						}
					}
                    if(count($emailuser) > 0){
                        $body = $this->renderPartial('_mailapproval', [
                            'approval' => $app,
							'name' => $name,
                            'url' => \Yii::$app->params['URL'].'/inventory/opname/view&code='.$approval->code,
                        ]);
                        
                        $logs_mail = new LogsMail();
                        $logs_mail->type = 'APPROVAL OPNAME';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = 'Approval Opname '. $app->code;
						$logs_mail->body = $body;
						$logs_mail->keterangan = '';
                        
                        $sendMail = \Yii::$app->mailer->compose()
                            ->setFrom(['pos@ptmma.co.id' => 'Notification Reminder Approval'])
                            ->setTo($logs_mail->email)
                            ->setSubject($logs_mail->subject)
                            ->setHtmlBody($body);
                        if($sendMail->send()){
                            $logs_mail->status = 1;
                            if(!$logs_mail->save()){
                                $success = false;
                                foreach($logs_mail->errors as $error=>$value){
                                    $message .= strtoupper($error).": ".$value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
    
                            $app->status=2;
                            if(!$app->save()){
                                $success = false;
                                foreach($app->errors as $error=>$value){
                                    $message .= strtoupper($error).": ".$value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'Gagal Send Email. Coba Sesaat Lagi.';
                        }
                    }else{
                        $success = false;
						$message = 'Email user to approval is EMPTY';
                    }
                }else{
                    $success = false;
					$message = 'Pengaturan Approval belum di setting.';
                }
            }else{
                $akhir = true;
            }
        }else{
            $success = false;
			$message = 'Pengaturan Approval belum di setting.';
        }
        return json_encode(['success'=>$success, 'message'=>$message, 'akhir'=>$akhir]);
    }

    function mailapproval_akhir($code, $comment=NULL)
    {
        $success = true;
		$message = '';
        $approval = InventoryOpnameApproval::find()
            ->where(['code'=>$code])
            ->orderBy(['urutan'=>SORT_DESC])
            ->one();
        $str_mail = '';
        if(isset($approval)){
            $body = $this->renderPartial('_mailapproval_akhir', [
                'approval' => $approval,
				'description' => $comment,
                'url' => \Yii::$app->params['URL'].'/inventory/opname/view&code='.$approval->code,
            ]);

            $logs_mail = new LogsMail();
            $logs_mail->type = 'APPROVAL OPNAME';
            $logs_mail->email = (isset($approval->opname->profile)) ? $approval->opname->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = 'Approval Opname '. $approval->code;
            $logs_mail->body = $body;
            $logs_mail->keterangan = '';
            
            $sendMail = \Yii::$app->mailer->compose()
                ->setFrom(['pos@ptmma.co.id' => 'Notification Reminder Approval'])
                ->setTo($logs_mail->email)
                ->setSubject($logs_mail->subject)
                ->setHtmlBody($body);
            if($sendMail->send()){
                $logs_mail->status = 1;
                if(!$logs_mail->save()){
                    $success = false;
                    foreach($logs_mail->errors as $error=>$value){
                        $message .= strtoupper($error).": ".$value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
            }else{
                $success = false;
                $message = 'Gagal Send Email. Coba Sesaat Lagi.';
            }
        }else{
            $success = false;
			$message = 'Pengaturan Approval belum di setting.';
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionPost($code)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($code);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->post=1;
                if($model->save()){
                    foreach($model->details as $val){
                        if($val->balance != 1){
                            $stockItem = InventoryStockItem::findOne(['item_code'=>$val->item_code, 'supplier_code'=>$val->supplier_code, 'status'=>1]);
                            if(empty($stockItem)){
                                $stockItem = new InventoryStockItem();
                            }
                            if(isset($stockItem)){
                                $konversi = $stockItem->satuanTerkecil($val->item_code, [
                                    0 => $val->qty_1,
                                    1 => $val->qty_2
                                ]);
                                $stockItem->attributes = $val->attributes;
                                $stockItem->onhand = $konversi;
                                if(!$stockItem->save()){
                                    $success = false;
                                    $message = (count($stockItem->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                    foreach($stockItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                                
                                $stockTransaction = new InventoryStockTransaction();
                                $stockTransaction->attributes = $val->attributes;
                                $stockTransaction->no_document = $model->code;
                                $stockTransaction->tgl_document = $model->date;
                                $stockTransaction->type_document = "STOCK OPNAME";
                                // MINUS -> OUT
                                if($val->balance == 0){
                                    $stockTransaction->status_document = "OUT";
                                    $stockTransaction->qty_out = $val->selisih;
                                    $stockTransaction->onsales = $stockItem->onsales + $val->selisih;
                                }
                                // LEBIH -> IN
                                else{
                                    $stockTransaction->status_document = "IN";
                                    $stockTransaction->qty_in = $konversi;
                                }
                                $stockTransaction->onhand = $konversi;
                                if(!$stockTransaction->save()){
                                    $success = false;
                                    $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                    foreach($stockTransaction->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                $message = 'Item {'.$val->item_code.'-'.$val->name.'} tidak ditemukan di Inventory Stock Item';
                            }
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST OPNAME: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->code.'] SUCCESS POST OPNAME.';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'code' => $model->code]);
                }else{
                    $transaction->rollback();
                }
            }catch(Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
            $logs =	[
                'type' => Logs::TYPE_USER,
                'description' => $message,
            ];
            Logs::addLog($logs);
        }else{
            $success = false;
            $message = 'Data Opname not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'code' => $model->code]);
    }
}