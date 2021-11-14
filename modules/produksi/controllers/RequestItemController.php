<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\LogsMailSend;
use app\models\User;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\Profile;
use app\modules\pengaturan\models\PengaturanApproval;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkDetail;
use app\modules\produksi\models\SpkRequestItem;
use app\modules\produksi\models\SpkRequestItemDetail;
use app\modules\produksi\models\SpkRequestItemApproval;
use app\modules\produksi\models\SpkRequestItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * RequestItemController implements the CRUD actions for SpkRequestItem model.
 */
class RequestItemController extends Controller
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
                            'actions' => [
                                'index', 'view', 'create', 'update', 'delete',
                                'search-spk', 'popup', 'send-approval', 'approval', 'post'
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-item')),
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
     * Lists all SpkRequestItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpkRequestItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpkRequestItem model.
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
        $approval = SpkRequestItemApproval::findOne(['no_request'=>$no_request, 'status'=>2]);
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
     * Creates a new SpkRequestItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $model = new SpkRequestItem();
        $detail = new SpkRequestItemDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $detail->load($this->request->post())) {
                $request = \Yii::$app->request;
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->attributes = $model->attributes;
                    $model->no_request = $model->generateCode();
                    $model->tgl_request = date('Y-m-d');
                    $model->user_id = \Yii::$app->user->id;
                    if($model->save()){
                        $dataDetail = $request->post('SpkRequestItemDetail');
                        if(!empty($detail->qty_order_1) || !empty($detail->qty_order_2)){
                            $detail->attributes = $model->attributes;
                            $detail->attributes = $detail->spkDetail->attributes;
                            $detail->attributes = (array)$dataDetail;
                            $detail->urutan = $detail->count +1;
                            $detail->total_order = 0;
                            $jumlahProses = $detail->jumlahProses();
                            if(!$detail->save()){
                                $success = false;
                                $message = (count($detail->errors) > 0) ? 'ERROR CREATE REQUEST ITEM DETAIL: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'QTY REQUEST KOSONG. SILAKAN ISI QTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE REQUEST ITEM: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $transaction->commit();
                        $message = '['.$model->no_request.'] SUCCESS CREATE REQUEST ITEM.';
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
        }

        return $this->render('create', [
            'model' => $model,
            'detail' => $detail,
        ]);
    }

    /**
     * Updates an existing SpkRequestItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_request)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($no_request);
        $detail = SpkRequestItemDetail::findOne(['no_request'=>$no_request]);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $detail->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    if($model->save()){
                        if(!empty($detail->qty_order_1) || !empty($detail->qty_order_2)){
                            $detail->attributes = $detail->attributes;
                            $jumlahProses = $detail->jumlahProses();
                            if(!$detail->save()){
                                $success = false;
                                $message = (count($detail->errors) > 0) ? 'ERROR UPDATE REQUEST ITEM DETAIL: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'QTY REQUEST KOSONG. SILAKAN ISI QTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE DATA REQUEST ITEM: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                    
                    if($success){
                        $transaction->commit();
                        $message = '['.$model->no_request.'] SUCCESS UPDATE REQUEST ITEM.';
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
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Post SPK.');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'detail' => $detail,
        ]);
    }

    /**
     * Deletes an existing SpkRequestItem model.
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
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Post SPK.');
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
                                    $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL REQUEST ITEM: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR DELETE REQUEST ITEM: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
        
                        if($success){
                            $transaction->commit();
                            $message = '['.$model->no_request.'] SUCCESS DELETE REQUEST ITEM.';
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
     * Finds the SpkRequestItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_request No Request
     * @return SpkRequestItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_request)
    {
        if (($model = SpkRequestItem::findOne($no_request)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearchSpk()
    {
        $success = true;
        $message = '';
        $model = [];
        if(!empty($_POST['no_spk'])){
            $spkDetail = SpkDetail::findOne(['no_spk'=>$_POST['no_spk']]);
            if(isset($spkDetail)){
                $model = [
                    'item_code' => $spkDetail['item_code'],
                    'item_name' => (isset($spkDetail->item)) ? $spkDetail->item['name'] : '',
                ];
            }else{
                $success = false;
                $message = 'No. SPK tidak ditemukan.';
            }
        }else{
            $success = false;
            $message = 'Masukkan No. SPK.';
        }
        return json_encode(['model'=>$model, 'success'=>$success, 'message'=>$message]);
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
                    $approvals = (new PengaturanApproval)->approval('request-item');
                    if(isset($approvals)){
                        $approvaln = SpkRequestItemApproval::findAll(['no_request'=>$no_request]);
                        if(count($approvaln) > 0)
                            SpkRequestItemApproval::deleteAll('no_request=:no_request', [':no_request'=>$no_request]);
                        foreach($approvals as $approval){
                            $app = new SpkRequestItemApproval();
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
                        $message = 'Setting approval Request Item belum ada. Silakan hubungi administrator utk melakukan setting approval.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS REQUEST ITEM: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $mailapproval = json_decode($this->mailapproval($model->no_request));
                    if($mailapproval->success){
                        $message = '['.$model->no_request.'] SUCCESS SEND APPROVAL REQUEST ITEM.';
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
            $message = 'Data Request Item not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }

    public function actionApproval()
    {
        $request = \Yii::$app->request;
        $data = $request->post('SpkRequestItemApproval');
        $success = true;
		$message = '';
        $model = $this->findModel($data['no_request']);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $approval = SpkRequestItemApproval::findOne(['no_request'=>$model->no_request, 'status'=>2]);
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
                                        $message = '['.$model->no_request.'] SUCCESS APPROVE REQUEST ITEM.';
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
                                        $message = '['.$model->no_request.'] SUCCESS REJECT REQUEST ITEM.';
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
            $message = 'Data Request Item not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }

    public function actionPopup()
    {
        $request = \Yii::$app->request;
        $approval = SpkRequestItemApproval::findOne(['no_request'=>$request->post('no_request'), 'status'=>2]);
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
                    foreach($model->details as $val){
                        $stockItem = $val->stock;
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
                                $stockTransaction->type_document = "REQUEST ITEM";
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
                    
                    // PROSES SIMPAN SPK
                    $spk = Spk::findOne(['no_spk'=>$model->no_spk, 'status'=>1]);
                    if(isset($spk)){
                        $nspk = new Spk();
                        $nspk->no_spk = $spk->generateCode();
                        $nspk->tgl_spk = date('Y-m-d');
                        $nspk->no_so = $spk->no_so;
                        $nspk->tgl_so = $spk->tgl_so;
                        $nspk->keterangan = 'DARI REQUEST ITEM';
                        if($nspk->save()){
                            foreach($model->details as $detail){
                                $spkDetail = new SpkDetail();
                                $spkDetail->attributes = $detail->attributes;
                                $spkDetail->attributes = $nspk->attributes;
                                if(!$spkDetail->save()){
                                    $success = false;
                                    $message = (count($spkDetail->errors) > 0) ? 'ERROR CREATE SPK DETAIL: ' : '';
                                    foreach($spkDetail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($nspk->errors) > 0) ? 'ERROR CREATE SPK: ' : '';
                            foreach($nspk->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Data SPK tidak ditemukan.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST REQUEST ITEM TO SPK: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->no_request.'] SUCCESS POST REQUEST ITEM TO SPK.';
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
            $message = 'Data Request Item not valid.';
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
        $approvals = SpkRequestItemApproval::find()->where(['no_request'=>$no_request])->orderBy(['urutan'=>SORT_ASC])->all();
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
                $app = SpkRequestItemApproval::findOne(['no_request'=>$no_request, 'urutan'=>$urutan]);
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
                            'url' => \Yii::$app->params['URL'].'/produksi/request-item/view&no_request='.$approval->no_request,
                        ]);
                        
                        $logs_mail = new LogsMailSend();
                        $logs_mail->type = 'APPROVAL REQUEST ITEM';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = 'Approval Request Item '. $app->no_request;
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
        $approval = SpkRequestItemApproval::find()
            ->where(['no_request'=>$no_request])
            ->orderBy(['urutan'=>SORT_DESC])
            ->one();
        $str_mail = '';
        if(isset($approval)){
            $body = $this->renderPartial('_mailapproval_akhir', [
                'approval' => $approval,
				'description' => $comment,
                'url' => \Yii::$app->params['URL'].'/produksi/request-item/view&no_request='.$approval->no_request,
            ]);

            $logs_mail = new LogsMailSend();
            $logs_mail->type = 'APPROVAL REQUEST ITEM';
            $logs_mail->email = (isset($approval->spkRequestItem->profile)) ? $approval->spkRequestItem->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = 'Approval Request Item '. $approval->no_request;
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
}