<?php

namespace app\modules\purchasing\controllers;

use app\models\Logs;
use app\models\LogsMail;
use app\models\User;
use app\modules\master\models\Profile;
use app\modules\pengaturan\models\PengaturanApproval;
use app\modules\purchasing\models\PurchaseInternal;
use app\modules\purchasing\models\PurchaseInternalDetail;
use app\modules\purchasing\models\PurchaseInternalApproval;
use app\modules\purchasing\models\PurchaseInternalSearch;
use app\modules\purchasing\models\TempPurchaseInternalDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * PurchaseInternalController implements the CRUD actions for PurchaseInternal model.
 */
class PurchaseInternalController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-internal')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'temp', 'get-temp', 'popup'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-internal')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'update-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-internal')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-internal')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-internal')),
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
     * Lists all PurchaseInternal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseInternalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseInternal model.
     * @param string $no_pi No Pi
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_pi)
    {
        $model = $this->findModel($no_pi);
        $typeuser = \Yii::$app->user->identity->profile->typeUser->value;
        $sendApproval = false;
        if($typeuser == 'ADMINISTRATOR' || $typeuser == 'ADMIN'){
            if($model->status_approval == 0 || $model->status_approval == 3){
                $sendApproval = true;
            }
        }
        $typeApproval = false;
        $approval = PurchaseInternalApproval::findOne(['no_pi'=>$no_pi, 'status'=>2]);
        if(isset($approval)){
            if(($model->status_approval==1) && ($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                $typeApproval = true;
            }
        }

        return $this->render('view', [
            'model' => $model,
            'sendApproval' => $sendApproval,
            'typeApproval' => $typeApproval,
            'typeuser' => $typeuser,
        ]);
    }

    /**
     * Creates a new PurchaseInternal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $profile = Profile::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('user_id')
            ->column();

        $success = true;
        $message = '';
        $temp = new TempPurchaseInternalDetail();
        $model = new PurchaseInternal();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->no_pi = $model->generateCode();
                    $model->user_id = \Yii::$app->user->id;
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new PurchaseInternalDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->no_pi = $model->no_pi;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE PO INTERNAL DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE PO INTERNAL: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE PO INTERNAL: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_pi.'] SUCCESS CREATE PO INTERNAL.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_pi' => $model->no_pi]);
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
            'profile' => $profile,
            'model' => $model,
            'temp' => $temp,
        ]);
    }

    /**
     * Updates an existing PurchaseInternal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_pi No Pi
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_pi)
    {
        $profile = Profile::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('user_id')
            ->column();

        $success = true;
        $message = '';
        $temp = new TempPurchaseInternalDetail();
        $model = $this->findModel($no_pi);
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
                                $detail = new PurchaseInternalDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE PO INTERNAL DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE PO INTERNAL: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE PO INTERNAL: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_pi.'] SUCCESS UPDATE PO INTERNAL.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_pi' => $model->no_pi]);
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
            }else if($model->status_approval == 2){
                \Yii::$app->session->setFlash('error', 'Dokumen ini sudah tidak bisa di Edit (Sudah Finish Approval).');
                return $this->redirect(['index']);
            }else{
                $this->emptyTemp();
                foreach($model->details as $detail){
                    $temp = new TempPurchaseInternalDetail();
                    $temp->attributes = $detail->attributes;
                    $temp->user_id = \Yii::$app->user->id;
                    if(!$temp->save()){
                        $message = (count($temp->errors) > 0) ? 'ERROR LOAD PO INTERNAL DETAIL: ' : '';
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
            'profile' => $profile,
            'model' => $model,
            'temp' => $temp,
        ]);
    }

    /**
     * Deletes an existing PurchaseInternal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_pi No Pi
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_pi)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_pi);
        if(isset($model)){
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
            }else if($model->status_approval == 2){
                \Yii::$app->session->setFlash('error', 'Dokumen ini sudah tidak bisa di Hapus (Sudah Finish Approval).');
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
                                $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL PO INTERNAL: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR DELETE PO INTERNAL: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
    
                    if($success){
                        $transaction->commit();
                        $message = '['.$model->no_pi.'] SUCCESS DELETE PO INTERNAL.';
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
     * Finds the PurchaseInternal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_pi No Pi
     * @return PurchaseInternal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_pi)
    {
        if (($model = PurchaseInternal::findOne($no_pi)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionTemp()
    {
        $temps = TempPurchaseInternalDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $total_order=0;
        foreach($temps as $temp){
            $total_order += $temp->total_order;
        }
        $model =  $this->renderAjax('_temp', ['temps'=>$temps]);
        return json_encode(['total_order'=>number_format($total_order), 'model'=>$model]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempPurchaseInternalDetail::find()->where(['id'=>$id])->asArray()->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('TempPurchaseInternalDetail');
            $temp = new TempPurchaseInternalDetail();
            $temp->attributes = (array)$data;
            $temp->urutan = $temp->count +1;
            $temp->user_id = \Yii::$app->user->id;
            if(!empty($request->post('PurchaseInternal')['no_pi'])){
                $temp->no_pi = $request->post('PurchaseInternal')['no_pi'];
            }
            $temp->total_order = $temp->totalBeli;
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
        if($request->isPost){
            $data = $request->post('TempPurchaseInternalDetail');
            $temp = $this->findTemp($data['id']);
            $temp->attributes = (array)$data;
            $temp->total_order = $temp->totalBeli;
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
        $temp = TempPurchaseInternalDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempPurchaseInternalDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempPurchaseInternalDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_purchase_internal_detail AUTO_INCREMENT=1')->query();
        }
    }

    public function actionSendApproval($no_pi)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_pi);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->status_approval = 1;
                if($model->save()){
                    $approvals = (new PengaturanApproval)->approval('purchase-order-internal');
                    if(isset($approvals)){
                        $approvaln = PurchaseInternalApproval::findAll(['no_pi'=>$no_pi]);
                        if(count($approvaln) > 0)
                            PurchaseInternalApproval::deleteAll('no_pi=:no_pi', [':no_pi'=>$no_pi]);
                        foreach($approvals as $approval){
                            $app = new PurchaseInternalApproval();
                            $app->attributes = $approval->attributes;
                            $app->no_pi = $no_pi;
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
                        $message = 'Setting approval Purchase Order Internal belum ada. Silakan hubungi administrator utk melakukan setting approval.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS PO INTERNAL: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $mailapproval = json_decode($this->mailapproval($model->no_pi));
                    if($mailapproval->success){
                        $message = '['.$model->no_pi.'] SUCCESS SEND APPROVAL PO INTERNAL.';
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
            $message = 'Data Purchase Order Internal not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_pi' => $model->no_pi]);
    }

    public function actionApproval()
    {
        $request = \Yii::$app->request;
        $data = $request->post('PurchaseInternalApproval');
        $success = true;
		$message = '';
        $model = $this->findModel($data['no_pi']);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $approval = PurchaseInternalApproval::findOne(['no_pi'=>$model->no_pi, 'status'=>2]);
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
                                $mailapproval = json_decode($this->mailapproval($model->no_pi));
                                if($mailapproval->success){
                                    $is_akhir = true;
                                    if($mailapproval->akhir){
                                        $mailakhir = json_decode($this->mailapproval_akhir($model->no_pi, $approval->comment));
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
                                        $message = '['.$model->no_pi.'] SUCCESS APPROVE PO INTERNAL.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_pi' => $model->no_pi]);
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
                                    $mailakhir = json_decode($this->mailapproval_akhir($model->no_pi, $approval->comment));
                                    if($mailakhir->success){
                                        $transaction->commit();
                                        $message = '['.$model->no_pi.'] SUCCESS REJECT PO INTERNAL.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_pi' => $model->no_pi]);
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
            $message = 'Data Purchase Order Internal not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_pi' => $model->no_pi]);
    }

    public function actionPopup()
    {
        $request = \Yii::$app->request;
        $approval = PurchaseInternalApproval::findOne(['no_pi'=>$request->post('no_pi'), 'status'=>2]);
        $model = $this->findModel($request->post('no_pi'));
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

    function mailapproval($no_pi)
    {
        $success = true;
		$message = '';
        $akhir = false;
		$urutan = 0;
		$profile = [];
        $approvals = PurchaseInternalApproval::find()->where(['no_pi'=>$no_pi])->orderBy(['urutan'=>SORT_ASC])->all();
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
                $app = PurchaseInternalApproval::findOne(['no_pi'=>$no_pi, 'urutan'=>$urutan]);
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
                            'url' => \Yii::$app->params['URL'].'/purchasing/purchase-internal/view&no_pi='.$approval->no_pi,
                        ]);
                        
                        $logs_mail = new LogsMail();
                        $logs_mail->type = 'APPROVAL PURCHASE ORDER INTERNAL';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = 'Approval Purchase Order Internal '. $app->no_pi;
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

    function mailapproval_akhir($no_pi, $comment=NULL)
    {
        $success = true;
		$message = '';
        $approval = PurchaseInternalApproval::find()
            ->where(['no_pi'=>$no_pi])
            ->orderBy(['urutan'=>SORT_DESC])
            ->one();
        $str_mail = '';
        if(isset($approval)){
            $body = $this->renderPartial('_mailapproval_akhir', [
                'approval' => $approval,
				'description' => $comment,
                'url' => \Yii::$app->params['URL'].'/purchasing/purchase-internal/view&no_pi='.$approval->no_pi,
            ]);

            $logs_mail = new LogsMail();
            $logs_mail->type = 'APPROVAL PURCHASE ORDER INTERNAL';
            $logs_mail->email = (isset($approval->poInternal->profile)) ? $approval->poInternal->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = 'Approval Purchase Order Internal '. $approval->no_pi;
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