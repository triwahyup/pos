<?php

namespace app\modules\purchasing\controllers;

use app\commands\Konstanta;
use app\models\Logs;
use app\models\LogsMailSend;
use app\models\User;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\Profile;
use app\modules\pengaturan\models\PengaturanApproval;
use app\modules\purchasing\models\PurchaseOrder;
use app\modules\purchasing\models\PurchaseOrderApproval;
use app\modules\purchasing\models\PurchaseOrderDetail;
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
                            'actions' => ['create', 'create-temp', 'send-approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-item', 'temp', 'get-temp', 'popup'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'update-temp', 'post'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
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
        $approval = PurchaseOrderApproval::findOne(['no_po'=>$no_po, 'status'=>2]);
        $is_userApproval = false;
        if(isset($approval)){
            if(($model->status_approval==1) && ($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                $is_userApproval = true;
            }
        }
        return $this->render('view', [
            'model' => $model,
            'is_userApproval' => $is_userApproval,
        ]);
    }

    /**
     * Creates a new PurchaseOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $supplier = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>Konstanta::TYPE_SUPPLIER, 'status' => 1])
            ->indexBy('code')
            ->column();
        $profile = Profile::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('user_id')
            ->column();
        
        $success = true;
        $message = '';
        $model = new PurchaseOrder();
        $model->no_po = $model->generateCode();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->total_order = str_replace(',','', $model->total_order);
                    $model->user_id = \Yii::$app->user->id;
                    if($model->save()){
                        if(count($model->temps) > 0){
                            foreach($model->temps as $temp){
                                $detail = new PurchaseOrderDetail();
                                $detail->attributes = $temp->attributes;
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
                        $message = 'CREATE PO: '.$model->no_po;
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
            'model' => $model,
            'supplier' => $supplier,
            'profile' => $profile,
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
        $supplier = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>Konstanta::TYPE_SUPPLIER, 'status' => 1])
            ->indexBy('code')
            ->column();
        $profile = Profile::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('user_id')
            ->column();

        $success = true;
        $message = '';
        $model = $this->findModel($no_po);
        if ($this->request->isPost) {
            if ($model->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->total_order = str_replace(',','', $model->total_order);
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
                        $message = 'UPDATE PO: '.$model->no_po;
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

        return $this->render('update', [
            'model' => $model,
            'supplier' => $supplier,
            'profile' => $profile,
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
                        $message = 'DELETE PO: '.$model->no_po;
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
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

    public function actionListItem($q)
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['concat(a.code, "-", a.name) as text', 'a.code id', 'b.name as satuan'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.status'=>1])
            ->andWhere('a.code LIKE "%'.$q.'%" OR a.name LIKE "%'.$q.'%"')
            ->asArray()
            ->all();
        return json_encode(['results'=>$model]);
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
        $temp = $this->findTemp($id);
        $data['id'] = $temp->id;
        $data['qty_order'] = $temp->qty_order;
        $data['ppn'] = $temp->ppn;
        $data['harga_beli'] = $temp->harga_beli;
        $data['satuan'] = $temp->satuan;
        $data['item_code'] = $temp->item_code;
        return json_encode($data);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        $po = $request->post('PurchaseOrder');
        if($request->isPost){
            $temp = new TempPurchaseOrderDetail();
            $temp->attributes = (array)$po;
            $temp->name = ($temp->item) ? $temp->item->name : '';
            $temp->urutan = count($temp->count) +1;
            $temp->user_id = \Yii::$app->user->id;
            
            $hargaBeli = str_replace(',', '', $temp->harga_beli);
            $temp->harga_beli = $hargaBeli;
            if(!empty($temp->ppn)){
                $ppn = $hargaBeli * $temp->qty_order / ($temp->ppn*100);
                $temp->total_order = ($hargaBeli * $temp->qty_order) - $ppn;
            }else{
                $temp->total_order = $hargaBeli * $temp->qty_order;
            }
            if($temp->save()){
                $message = 'CREATE TEMP SUCCESSFULLY';
            }else{
                $success = false;
                foreach($temp->errors as $error => $value){
                    $message = $value[0].', ';
                }
                $message = substr($message, 0, -2);
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
        $message = '';
        $po = $request->post('PurchaseOrder');
        if($request->isPost){
            $temp = $this->findTemp($po['id']);
            $temp->attributes = (array)$po;
            $temp->name = ($temp->item) ? $temp->item->name : '';
            
            $hargaBeli = str_replace(',', '', $temp->harga_beli);
            $temp->harga_beli = $hargaBeli;
            if(!empty($temp->ppn)){
                $ppn = $hargaBeli * $temp->qty_order / ($temp->ppn*100);
                $temp->total_order = ($hargaBeli * $temp->qty_order) - $ppn;
            }else{
                $temp->total_order = $hargaBeli * $temp->qty_order;
            }
            if($temp->save()){
                $message = 'UPDATE TEMP SUCCESSFULLY';
            }else{
                $success = false;
                foreach($temp->errors as $error => $value){
                    $message = $value[0].', ';
                }
                $message = substr($message, 0, -2);
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteTemp($id)
    {
        $success = true;
        $message = '';
        $temp = $this->findTemp($id);
        if(isset($temp)){
            if($temp->delete()){
                foreach($temp->count as $index=>$val){
                    $val->urutan = $index +1;
                    if(!$val->save()){
                        $success = false;
                        foreach($val->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }
                $message = 'DELETE TEMP SUCCESSFULLY';
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
                    $approvals = (new PengaturanApproval)->approval('purchase-order');
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
                        $message = 'PURCHASE ORDER: SEND APPROVAL '.$model->no_po.' SUCCESS';
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
                                        $message = 'APPROVE PO: '. $approval->no_po .' SUCCESS.';
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
                                        $message = 'REJECT PO: '. $approval->no_po;
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
						$name = 'Yth. Bpk/Ibu '. $app->profile->name;
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
                        $subject = 'Approval Purchase Order '. $app->no_po;
                        $logs_mail = new LogsMailSend();
                        $logs_mail->type = 'APPROVAL PURCHASE ORDER';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = $subject;
						$logs_mail->body = $body;
						$logs_mail->keterangan = '';
						$logs_mail->status = 0;
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
						$message = 'Email user to approval is EMPTY';
                    }
                }else{
                    $success = false;
					$message = 'Approval is EMPTY';
                }
            }else{
                $akhir = true;
            }
        }else{
            $success = false;
			$message = 'APPROVAL is EMPTY';
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

            $subject = 'Approval Purchase Order '. $approval->no_po;
            $logs_mail = new LogsMailSend();
            $logs_mail->type = 'APPROVAL PURCHASE ORDER';
            $logs_mail->email = (isset($approval->po->profile)) ? $approval->po->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = $subject;
            $logs_mail->body = $body;
            $logs_mail->keterangan = '';
            $logs_mail->status = 0;
            if(!$logs_mail->save()){
                $success = false;
                foreach($logs_mail->errors as $error=>$value){
                    $message .= strtoupper($error).": ".$value[0].', ';
                }
                $message = substr($message, 0, -2);
            }
        }else{
            $success = false;
			$message = 'APPROVAL is EMPTY';
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
}
