<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\LogsMail;
use app\models\User;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\MasterMaterialItem;
use app\modules\pengaturan\models\PengaturanApproval;
use app\modules\produksi\models\Spk;
use app\modules\sales\models\RequestOrder;
use app\modules\sales\models\RequestOrderApproval;
use app\modules\sales\models\RequestOrderItem;
use app\modules\sales\models\RequestOrderSearch;
use app\modules\sales\models\TempRequestOrderItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * RequestOrderController implements the CRUD actions for RequestOrder model.
 */
class RequestOrderController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-item', 'search', 'autocomplete', 'item', 'popup', 'temp', 'get-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'post', 'update-temp', 'send-approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[U]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[D]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[A]')),
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
     * Lists all RequestOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequestOrder model.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_request)
    {
        $model = $this->findModel($no_request);
        $typeuser = \Yii::$app->user->identity->profile->typeUser->value;
        $sendApproval = false;
        $postSpk = false;
        if($typeuser == 'ADMINISTRATOR' || $typeuser == 'ADMIN'){
            if($model->status_approval == 0 || $model->status_approval == 3){
                $sendApproval = true;
            }
            if($model->status_approval == 2 && ($model->post == 0 || empty($model->post))){
                $postSpk = true;
            }
        }

        $typeApproval = false;
        $approval = RequestOrderApproval::findOne(['no_request'=>$no_request, 'status'=>2]);
        if(isset($approval)){
            if(($model->status_approval==1) && ($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                $typeApproval = true;
            }
        }

        return $this->render('view', [
            'model' => $model,
            'sendApproval' => $sendApproval,
            'postSpk' => $postSpk,
            'typeApproval' => $typeApproval,
            'typeuser' => $typeuser,
        ]);
    }

    /**
     * Creates a new RequestOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $model = new RequestOrder();
        $temp = new TempRequestOrderItem();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->no_request = $model->generateCode();
                    $model->user_id = \Yii::$app->user->id;
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $item = new RequestOrderItem();
                                $item->attributes = $temp->attributes;
                                $item->no_request = $model->no_request;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR CREATE REQUEST ORDER ITEM: ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE REQUEST ORDER: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE REQUEST ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_request.'] SUCCESS CREATE REQUEST ORDER.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_request' => $model->no_request]);
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
            'spk' => Spk::find()
                ->select(['no_spk'])
                ->where(['status_produksi'=>[2,3]])
                ->indexBy('no_spk')
                ->column(),
        ]);
    }

    /**
     * Updates an existing RequestOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_request)
    {
        $spk = Spk::find()->select(['no_spk'])->where(['status_produksi'=>[2,3]])->indexBy('no_spk')->column();
        $success = true;
        $message = '';
        $model = $this->findModel($no_request);
        $temp = new TempRequestOrderItem();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->status_approval = null;
                    if($model->save()){
                        if(count($model->temps) > 0){
                            foreach($model->items as $empty)
                                $empty->delete();
                            foreach($model->temps as $temp){
                                $item = new RequestOrderItem();
                                $item->attributes = $temp->attributes;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR UPDATE REQUEST ORDER ITEM: ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE PO: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE REQUEST ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_request.'] SUCCESS UPDATE REQUEST ORDER.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_request' => $model->no_request]);
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
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di post SPK.');
                    return $this->redirect(['index']);
                }else{
                    $this->emptyTemp();
                    foreach($model->items as $item){
                        $temp = new TempRequestOrderItem();
                        $temp->attributes = $item->attributes;
                        $temp->user_id = \Yii::$app->user->id;
                        if(!$temp->save()){
                            $message = (count($temp->errors) > 0) ? 'ERROR LOAD REQUEST ORDER ITEM: ' : '';
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
            'spk' => Spk::find()
                ->select(['no_spk'])
                ->where(['status_produksi'=>[2,3]])
                ->indexBy('no_spk')
                ->column()
        ]);
    }

    /**
     * Deletes an existing RequestOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_request)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_request);
        if(isset($model)){
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
            }else{
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di post SPK.');
                }else{
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();
                    try{
                        $model->status = 0;
                        if($model->save()){
                            foreach($model->items as $item){
                                $item->status = 0;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR DELETE DETAIL REQUEST ORDER: ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR DELETE REQUEST ORDER: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }

                        if($success){
                            $transaction->commit();
                            $message = '['.$model->no_request.'] SUCCESS DELETE REQUEST ORDER.';
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
     * Finds the RequestOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_request No Request
     * @return RequestOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_request)
    {
        if (($model = RequestOrder::findOne($no_request)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListItem()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.status'=>1])
            ->orderBy(['a.code'=>SORT_ASC])
            ->limit(10)
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_item', ['model'=>$model])]);
    }

    public function actionAutocomplete()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = MasterMaterialItem::find()
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
            $model = MasterMaterialItem::find()
                ->alias('a')
                ->select(['a.*', 'b.composite'])
                ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
                ->leftJoin('master_kode c', 'c.code = a.type_code')
                ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_item', ['model'=>$model])]);
    }

    public function actionItem()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.*', 'a.name as item_name', 'c.composite'])
            ->leftJoin('master_material_item_pricelist b', 'b.item_code = a.code')
            ->leftJoin('master_satuan c', 'c.code = a.satuan_code')
            ->where(['a.code'=>$_POST['code'], 'a.status'=>1, 'b.status_active' => 1])
            ->asArray()
            ->one();
        if(empty($model)){
            $model = [];
        }
        return json_encode($model);
    }

    public function actionTemp()
    {
        $temps = TempRequestOrderItem::findAll(['user_id'=> \Yii::$app->user->id]);
        $model =  $this->renderAjax('_temp', ['temps'=>$temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempRequestOrderItem::find()->where(['id'=>$id])->asArray()->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $dataTemp = $request->post('TempRequestOrderItem');
            $model = Spk::findOne(['no_spk'=>$dataTemp['no_spk']]);
            if(isset($model)){
                $temp = new TempRequestOrderItem();
                $temp->attributes = (array)$dataTemp;
                $temp->attributes = $model->attributes;
                $temp->attributes = $temp->item->attributes;
                if(!empty($temp->qty_order_1) || !empty($temp->qty_order_2)){
                    if(isset($temp->itemPricelist)){
                        $temp->attributes = $temp->itemPricelist->attributes;
                        $temp->no_request = 'tmp';
                        $temp->urutan = $temp->count +1;
                        $temp->total_order = $temp->totalOrder;
                        $temp->user_id = \Yii::$app->user->id;
                        if(!$temp->save()){
                            $success = false;
                            foreach($temp->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Pricelist untuk item '.$dataTemp['item_name'].' belum di setting.';
                    }
                }else{
                    $success = false;
                    $message = 'Qty belum diisi. Silakan isi Qty terlebih dahulu.';
                }
            }else{
                $success = false;
                $message = 'No. SPK tidak ditemukan di Surat Perintah Kerja.';
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
            $dataTemp = $request->post('TempRequestOrderItem');
            $model = Spk::findOne(['no_spk'=>$dataTemp['no_spk']]);
            if(isset($model)){
                $temp = $this->findTemp($dataTemp['id']);
                $temp->attributes = (array)$dataTemp;
                $temp->attributes = $model->attributes;
                $temp->attributes = $temp->item->attributes;
                if(!empty($temp->qty_order_1) || !empty($temp->qty_order_2)){
                    if(isset($temp->itemPricelist)){
                        $temp->attributes = $temp->itemPricelist->attributes;
                        $dataHeader = $request->post('RequestOrder');
                        $temp->no_request = (!empty($dataHeader['no_request'])) ? $dataHeader['no_request'] : 'tmp';
                        $temp->urutan = $dataTemp['urutan'];
                        $temp->total_order = $temp->totalOrder;
                        $temp->user_id = \Yii::$app->user->id;
                        if(!$temp->save()){
                            $success = false;
                            foreach($temp->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Pricelist untuk item '.$dataTemp['item_name'].' belum di setting.';
                    }
                }else{
                    $success = false;
                    $message = 'Qty belum diisi. Silakan isi Qty terlebih dahulu.';
                }
            }else{
                $success = false;
                $message = 'No. SPK tidak ditemukan di Surat Perintah Kerja.';
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
        $temp = TempRequestOrderItem::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempRequestOrderItem::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempRequestOrderItem::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_request_order_item AUTO_INCREMENT=1')->query();
        }
    }

    public function actionPopup()
    {
        $request = \Yii::$app->request;
        $approval = RequestOrderApproval::findOne(['no_request'=>$request->post('no_request'), 'status'=>2]);
        $model = $this->findModel($request->post('no_request'));
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

    public function actionSendApproval($no_request)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_request);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->status_approval = 1;
                if($model->save()){
                    $approvals = (new PengaturanApproval)->approval('request-order');
                    if(isset($approvals)){
                        $approvaln = RequestOrderApproval::findAll(['no_request'=>$no_request]);
                        if(count($approvaln) > 0)
                            RequestOrderApproval::deleteAll('no_request=:no_request', [':no_request'=>$no_request]);
                        foreach($approvals as $approval){
                            $app = new RequestOrderApproval();
                            $app->attributes = $approval->attributes;
                            $app->no_request = $no_request;
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
                        $message = 'Setting approval Request Order belum ada. Silakan hubungi administrator utk melakukan setting approval.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS REQUEST ORDER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $mailapproval = json_decode($this->mailapproval($model->no_request));
                    if($mailapproval->success){
                        $message = '['.$model->no_request.'] SUCCESS SEND APPROVAL REQUEST ORDER.';
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
            $message = 'Data Request Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }

    public function actionApproval()
    {
        $request = \Yii::$app->request;
        $data = $request->post('RequestOrderApproval');
        $success = true;
		$message = '';
        $model = $this->findModel($data['no_request']);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $approval = RequestOrderApproval::findOne(['no_request'=>$model->no_request, 'status'=>2]);
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
                                $mailapproval = json_decode($this->mailapproval($model->no_request));
                                if($mailapproval->success){
                                    $is_akhir = true;
                                    if($mailapproval->akhir){
                                        $mailakhir = json_decode($this->mailapproval_akhir($model->no_request, $approval->comment));
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
                                        $message = '['.$model->no_request.'] SUCCESS APPROVE REQUEST ORDER.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_request' => $model->no_request]);
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
                                    $mailakhir = json_decode($this->mailapproval_akhir($model->no_request, $approval->comment));
                                    if($mailakhir->success){
                                        $transaction->commit();
                                        $message = '['.$model->no_request.'] SUCCESS REJECT REQUEST ORDER.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_request' => $model->no_request]);
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
            $message = 'Data Purchase Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }

    function mailapproval($no_request)
    {
        $success = true;
		$message = '';
        $akhir = false;
		$urutan = 0;
		$profile = [];
        $approvals = RequestOrderApproval::find()->where(['no_request'=>$no_request])->orderBy(['urutan'=>SORT_ASC])->all();
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
                $app = RequestOrderApproval::findOne(['no_request'=>$no_request, 'urutan'=>$urutan]);
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
                            'url' => \Yii::$app->params['URL'].'/sales/request-order/view&no_request='.$approval->no_request,
                        ]);
                        
                        $logs_mail = new LogsMail();
                        $logs_mail->type = 'APPROVAL REQUEST ORDER';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = 'Approval Request Order '. $app->no_request;
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

    function mailapproval_akhir($no_request, $comment=NULL)
    {
        $success = true;
		$message = '';
        $approval = RequestOrderApproval::find()
            ->where(['no_request'=>$no_request])
            ->orderBy(['urutan'=>SORT_DESC])
            ->one();
        $str_mail = '';
        if(isset($approval)){
            $body = $this->renderPartial('_mailapproval_akhir', [
                'approval' => $approval,
				'description' => $comment,
                'url' => \Yii::$app->params['URL'].'/sales/request-order/view&no_request='.$approval->no_request,
            ]);

            $logs_mail = new LogsMail();
            $logs_mail->type = 'APPROVAL REQUEST ORDER';
            $logs_mail->email = (isset($approval->request->profile)) ? $approval->request->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = 'Approval Request Order '. $approval->no_request;
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

    public function actionPost($no_request)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_request);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->post=1;
                if($model->save()){
                    // PROSES KURANG STOK
                    $stock = 0;
                    foreach($model->items as $val){
                        $stockItem = $val->inventoryStock;
                        if(isset($stockItem)){
                            $stock = $stockItem->satuanTerkecil($val->item_code, [
                                0=>$val->qty_order_1,
                                1=>$val->qty_order_2
                            ]);
                            if($stockItem->onhand > $stock){
                                $stockItem->onhand = $stockItem->onhand - $stock;
                                $stockItem->onsales = $stockItem->onsales + $stock;
                                if(!$stockItem->save()){
                                    $success = false;
                                    $message = (count($stockItem->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                    foreach($stockItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $stockTransaction = new InventoryStockTransaction();
                                $stockTransaction->attributes = $stockItem->attributes;
                                $stockTransaction->no_document = $model->no_request;
                                $stockTransaction->tgl_document = $model->tgl_request;
                                $stockTransaction->type_document = "REQUEST ORDER";
                                $stockTransaction->status_document = "OUT";
                                $stockTransaction->qty_out = $stock;
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
                                $message = 'SISA STOCK ITEM '.$val->item_code.' TIDAK MENCUKUPI. SISA '.$stockItem->onhand;
                            }
                        }else{
                            $success = false;
                            $message = 'STOCK ITEM '.$val->item_code.' TIDAK DITEMUKAN.';
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST REQUEST ORDER TO SPK: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->no_request.'] SUCCESS POST REQUEST ORDER TO SPK.';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'no_request' => $model->no_request]);
                }else{
                    $transaction->rollBack();
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
            $message = 'Data Request Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }
}
