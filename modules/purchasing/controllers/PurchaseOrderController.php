<?php

namespace app\modules\purchasing\controllers;

use app\models\DataList;
use app\models\Logs;
use app\models\LogsMail;
use app\models\User;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\Profile;
use app\modules\pengaturan\models\PengaturanApproval;
use app\modules\purchasing\models\PurchaseOrder;
use app\modules\purchasing\models\PurchaseOrderApproval;
use app\modules\purchasing\models\PurchaseOrderDetail;
use app\modules\purchasing\models\PurchaseOrderInvoice;
use app\modules\purchasing\models\PurchaseOrderInvoiceDetail;
use app\modules\purchasing\models\PurchaseOrderSearch;
use app\modules\purchasing\models\TempPurchaseOrderDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * PurchaseOrderController implements the CRUD actions for PurchaseOrder model.
 */
class PurchaseOrderController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order-material[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [
                                'index', 'view', 'list-item', 'temp', 'get-temp', 'popup', 'search', 'item', 'autocomplete',
                                'on-change-term-in', 'on-input-term-in'
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order-material[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'update-temp', 'post', 'send-approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order-material[U]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order-material[D]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order-material[A]')),
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
     * Lists all PurchaseOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseOrder model.
     * @param string $no_po No Po
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_po)
    {
        $model = $this->findModel($no_po);
        $sendApproval = false;
        $postInvoice = false;
        if($model->status_approval == 0 || $model->status_approval == 3){
            $sendApproval = true;
        }
        if($model->status_approval == 2 && ($model->post == 0 || empty($model->post))){
            $postInvoice = true;
        }
        
        $typeApproval = false;
        $approval = PurchaseOrderApproval::findOne(['no_po'=>$no_po, 'status'=>2]);
        if(isset($approval)){
            if(($model->status_approval==1) && ($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                $typeApproval = true;
            }
        }
        
        return $this->render('view', [
            'model' => $model,
            'sendApproval' => $sendApproval,
            'postInvoice' => $postInvoice,
            'typeApproval' => $typeApproval,
        ]);
    }

    /**
     * Creates a new PurchaseOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $temp = new TempPurchaseOrderDetail();
        $model = new PurchaseOrder();
        $dataList = DataList::setListColumn();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->no_po = $model->generateCode();
                    $model->user_id = \Yii::$app->user->id;
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new PurchaseOrderDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->no_po = $model->no_po;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE PO DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE PO: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE PO: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_po.'] SUCCESS CREATE PO.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_po' => $model->no_po]);
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
            'dataList' => $dataList,
            'model' => $model,
            'temp' => $temp,
        ]);
    }

    /**
     * Updates an existing PurchaseOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_po No Po
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_po)
    {
        $success = true;
        $message = '';
        $temp = new TempPurchaseOrderDetail();
        $model = $this->findModel($no_po);
        $dataList = DataList::setListColumn();
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
                                $detail = new PurchaseOrderDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE PO DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
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
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE PO: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_po.'] SUCCESS UPDATE PO.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_po' => $model->no_po]);
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
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Invoice Order.');
                    return $this->redirect(['index']);
                }else{
                    $this->emptyTemp();
                    foreach($model->details as $detail){
                        $temp = new TempPurchaseOrderDetail();
                        $temp->attributes = $detail->attributes;
                        $temp->user_id = \Yii::$app->user->id;
                        if(!$temp->save()){
                            $message = (count($temp->errors) > 0) ? 'ERROR LOAD PO DETAIL: ' : '';
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
            'dataList' => $dataList,
            'model' => $model,
            'temp' => $temp,
        ]);
    }

    /**
     * Deletes an existing PurchaseOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_po No Po
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_po)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_po);
        if(isset($model)){
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
            }else{
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Invoice Order.');
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
                                    $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL PO: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR DELETE PO: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
        
                        if($success){
                            $transaction->commit();
                            $message = '['.$model->no_po.'] SUCCESS DELETE PO.';
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
     * Finds the PurchaseOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_po No Po
     * @return PurchaseOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_po)
    {
        if (($model = PurchaseOrder::findOne($no_po)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionOnChangeTermIn($supplier_code, $tgl_so)
    {
        $model = MasterPerson::find()->where(['code'=>$supplier_code])->asArray()->one();
        $termIn = (!empty($model['term_in'])) ? $model['term_in'] : 0;
        $top = date('d-m-Y', strtotime('+'.$termIn.' days', strtotime($tgl_so)));
        $tgl_tempo = '<i class="text-muted font-size-10">Tgl. Jatuh Tempo Pembayaran: '.$top.'</i>';
        return json_encode(['term_in'=>$termIn, 'tgl_tempo'=>$tgl_tempo]);
    }

    public function actionOnInputTermIn($tgl_po, $term_in)
    {
        $termIn = (!empty($term_in)) ? $term_in : 0;
        $top = date('d-m-Y', strtotime('+'.$termIn.' days', strtotime($tgl_po)));
        $tgl_tempo = '<i class="text-muted font-size-10">Tgl. Jatuh Tempo Pembayaran: '.$top.'</i>';
        return json_encode(['tgl_tempo'=>$tgl_tempo]);
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
            ->select(['a.*', 'b.*', 'a.name as item_name'])
            ->leftJoin('master_material_pricelist b', 'b.item_code = a.code')
            ->leftJoin('master_satuan c', 'c.code = a.satuan_code')
            ->where(['item_code'=>$_POST['code'], 'supplier_code'=>$_POST['supplier'], 'a.status'=>1, 'status_active' => 1])
            ->asArray()
            ->one();
        if(empty($model)){
            $model = [];
        }
        return json_encode($model);
    }

    public function actionTemp()
    {
        $temps = TempPurchaseOrderDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $total_order=0;
        foreach($temps as $temp){
            $total_order += $temp->total_order;
        }
        $model =  $this->renderAjax('_temp', [
            'temps'=>$temps,
        ]);
        return json_encode(['total_order'=>number_format($total_order), 'model'=>$model]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempPurchaseOrderDetail::find()
            ->select(['*', 'name as item_name'])
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
            $temp = new TempPurchaseOrderDetail();
            $dataHeader = $request->post('PurchaseOrder');
            $temp->attributes = (array)$dataHeader;
            $dataTemp = $request->post('TempPurchaseOrderDetail');
            $temp->attributes = (array)$dataTemp;
            if(!empty($dataTemp['item_name'])){
                if($dataTemp['qty_order_1'] > 0){
                    if(isset($temp->priceListActive)){
                        $temp->attributes = $temp->item->attributes;
                        $temp->attributes = $temp->satuan->attributes;
                        $temp->attributes = $temp->priceListActive->attributes;
                        $temp->name = $temp->item->name;
                        $temp->no_po = (!empty($dataHeader['no_po'])) ? $dataHeader['no_po'] : 'tmp';
                        $temp->urutan = $temp->count +1;
                        $temp->user_id = \Yii::$app->user->id;
                        $temp->total_order = $temp->totalBeli;
                        if(!$temp->save()){
                            $success = false;
                            foreach($temp->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Harga Beli / Harga Jual masih kosong. Silakan input harga terlebih dahulu di menu Master Material.';
                    }
                }else{
                    $success = false;
                    $message = 'Qty wajib diisi.';
                }
            }else{
                $success = false;
                $message = 'Material wajib diisi.';
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
            $dataHeader = $request->post('PurchaseOrder');
            $dataTemp = $request->post('TempPurchaseOrderDetail');
            $temp = $this->findTemp($dataTemp['id']);
            $temp->attributes = (array)$dataHeader;
            $temp->attributes = (array)$dataTemp;
            if(!empty($dataTemp['item_name'])){
                if($dataTemp['qty_order_1'] > 0){
                    if(isset($temp->priceListActive)){
                        $temp->attributes = $temp->item->attributes;
                        $temp->attributes = $temp->satuan->attributes;
                        $temp->attributes = $temp->priceListActive->attributes;
                        $temp->name = $temp->item->name;
                        $temp->total_order = $temp->totalBeli;
                        if(!$temp->save()){
                            $success = false;
                            foreach($temp->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Harga Beli / Harga Jual masih kosong. Silakan mengisikan harga terlebih dahulu di menu Master Material.';
                    }
                }else{
                    $success = false;
                    $message = 'Qty belum diisi.';
                }
            }else{
                $success = false;
                $message = 'Material belum diisi.';
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
        $temp = TempPurchaseOrderDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempPurchaseOrderDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempPurchaseOrderDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_purchase_order_detail AUTO_INCREMENT=1')->query();
        }
    }

    public function actionSendApproval($no_po)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_po);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->status_approval = 1;
                if($model->save()){
                    $approvals = (new PengaturanApproval)->approval('po-material');
                    if(isset($approvals)){
                        $approvaln = PurchaseOrderApproval::findAll(['no_po'=>$no_po]);
                        if(count($approvaln) > 0)
                            PurchaseOrderApproval::deleteAll('no_po=:no_po', [':no_po'=>$no_po]);
                        foreach($approvals as $approval){
                            $app = new PurchaseOrderApproval();
                            $app->attributes = $approval->attributes;
                            $app->no_po = $no_po;
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
                        $message = 'Setting approval Purchase Order belum ada. Silakan hubungi administrator utk melakukan setting approval.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS PO: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $mailapproval = json_decode($this->mailapproval($model->no_po));
                    if($mailapproval->success){
                        $message = '['.$model->no_po.'] SUCCESS SEND APPROVAL PO.';
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
            $message = 'Data Purchase Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_po' => $model->no_po]);
    }

    public function actionApproval()
    {
        $request = \Yii::$app->request;
        $data = $request->post('PurchaseOrderApproval');
        $success = true;
		$message = '';
        $model = $this->findModel($data['no_po']);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $approval = PurchaseOrderApproval::findOne(['no_po'=>$model->no_po, 'status'=>2]);
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
                                $mailapproval = json_decode($this->mailapproval($model->no_po));
                                if($mailapproval->success){
                                    $is_akhir = true;
                                    if($mailapproval->akhir){
                                        $mailakhir = json_decode($this->mailapproval_akhir($model->no_po, $approval->comment));
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
                                        $message = '['.$model->no_po.'] SUCCESS APPROVE PO.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_po' => $model->no_po]);
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
                                    $mailakhir = json_decode($this->mailapproval_akhir($model->no_po, $approval->comment));
                                    if($mailakhir->success){
                                        $transaction->commit();
                                        $message = '['.$model->no_po.'] SUCCESS REJECT PO.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_po' => $model->no_po]);
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
        return $this->redirect(['view', 'no_po' => $model->no_po]);
    }

    public function actionPopup()
    {
        $request = \Yii::$app->request;
        $approval = PurchaseOrderApproval::findOne(['no_po'=>$request->post('no_po'), 'status'=>2]);
        $model = $this->findModel($request->post('no_po'));
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

    function mailapproval($no_po)
    {
        $success = true;
		$message = '';
        $akhir = false;
		$urutan = 0;
		$profile = [];
        $approvals = PurchaseOrderApproval::find()->where(['no_po'=>$no_po])->orderBy(['urutan'=>SORT_ASC])->all();
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
                $app = PurchaseOrderApproval::findOne(['no_po'=>$no_po, 'urutan'=>$urutan]);
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
                            'url' => \Yii::$app->params['URL'].'/purchasing/purchase-order/view&no_po='.$approval->no_po,
                        ]);
                        
                        $logs_mail = new LogsMail();
                        $logs_mail->type = 'APPROVAL PURCHASE ORDER';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = 'Approval Purchase Order '. $app->no_po;
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

    function mailapproval_akhir($no_po, $comment=NULL)
    {
        $success = true;
		$message = '';
        $approval = PurchaseOrderApproval::find()
            ->where(['no_po'=>$no_po])
            ->orderBy(['urutan'=>SORT_DESC])
            ->one();
        $str_mail = '';
        if(isset($approval)){
            $body = $this->renderPartial('_mailapproval_akhir', [
                'approval' => $approval,
				'description' => $comment,
                'url' => \Yii::$app->params['URL'].'/purchasing/purchase-order/view&no_po='.$approval->no_po,
            ]);

            $logs_mail = new LogsMail();
            $logs_mail->type = 'APPROVAL PURCHASE ORDER';
            $logs_mail->email = (isset($approval->po->profile)) ? $approval->po->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = 'Approval Purchase Order '. $approval->no_po;
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

    public function actionPost($no_po)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_po);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->post=1;
                if($model->save()){
                    $invoiceOrder = new PurchaseOrderInvoice();
                    $invoiceOrder->attributes = $model->attributes;
                    $invoiceOrder->no_invoice = $invoiceOrder->generateCode();
                    $invoiceOrder->post=0;
                    if($invoiceOrder->save()){
                        foreach($model->details as $detail){
                            $invoiceOrderDetail = new PurchaseOrderInvoiceDetail();
                            $invoiceOrderDetail->attributes = $detail->attributes;
                            $invoiceOrderDetail->no_invoice = $invoiceOrder->no_invoice;
                            if(!$invoiceOrderDetail->save()){
                                $success = false;
                                $message = (count($invoiceOrderDetail->errors) > 0) ? 'ERROR CREATE INVOICE ORDER DETAIL: ' : '';
                                foreach($invoiceOrderDetail->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($invoiceOrder->errors) > 0) ? 'ERROR CREATE INVOICE ORDER: ' : '';
                        foreach($invoiceOrder->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST PO TO INVOICE ORDER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->no_po.'] SUCCESS POST PO TO INVOICE ORDER.';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'no_po' => $model->no_po]);
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
            $message = 'Data Purchase Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_po' => $model->no_po]);
    }
}