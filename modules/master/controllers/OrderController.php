<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterOrder;
use app\modules\master\models\MasterOrderDetail;
use app\modules\master\models\MasterOrderProduksiDetail;
use app\modules\master\models\TempMasterOrderDetail;
use app\modules\master\models\TempMasterOrderProduksiDetail;
use app\modules\master\models\MasterOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for MasterOrder model.
 */
class OrderController extends Controller
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
                            'actions' => ['create', 'create-temp', 'create-temp-produksi'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-order')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-item', 'temp', 'get-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'update-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp', 'delete-temp-produksi'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-order')),
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
     * Lists all MasterOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterOrder model.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($code)
    {
        return $this->render('view', [
            'model' => $this->findModel($code),
        ]);
    }

    /**
     * Creates a new MasterOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $model = new MasterOrder();
        $temp = new TempMasterOrderDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->code = $model->generateCode();
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new MasterOrderDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->order_code = $model->code;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE DATA ORDER DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE DATA ORDER: DETAIL IS EMPTY.';
                        }

                        if(count($model->tempsProduksi()) > 0){
                            foreach($model->tempsProduksi() as $temp){
                                $detail = new MasterOrderProduksiDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->order_code = $model->code;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE DATA PRODUKSI DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE DATA ORDER: PROSES PRODUKSI IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE DATA ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS CREATE ORDER.';
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
        ]);
    }

    /**
     * Updates an existing MasterOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($code);
        $temp = new TempMasterOrderDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    if($model->save()){
                        if(count($model->temps) > 0){
                            foreach($model->details as $empty)
                                $empty->delete();
                            foreach($model->temps as $temp){
                                $detail = new MasterOrderDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE DATA ORDER DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE DATA ORDER: DETAIL IS EMPTY.';
                        }

                        if(count($model->tempsProduksi) > 0){
                            foreach($model->detailsProduksi as $empty)
                                $empty->delete();
                            foreach($model->tempsProduksi as $temp){
                                $detail = new MasterOrderProduksiDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE DATA PRODUKSI DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE DATA ORDER: PROSES PRODUKSI IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE DATA ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS UPDATE ORDER.';
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
            $this->emptyTemp();
            foreach($model->details as $detail){
                $temp = new TempMasterOrderDetail();
                $temp->attributes = $detail->attributes;
                $temp->user_id = \Yii::$app->user->id;
                if(!$temp->save()){
                    $message = (count($temp->errors) > 0) ? 'ERROR LOAD DATA ORDER DETAIL: ' : '';
                    foreach($temp->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                    \Yii::$app->session->setFlash('error', $message);
                }
            }
            foreach($model->detailsProduksi as $detail){
                $temp = new TempMasterOrderProduksiDetail();
                $temp->attributes = $detail->attributes;
                $temp->user_id = \Yii::$app->user->id;
                if(!$temp->save()){
                    $message = (count($temp->errors) > 0) ? 'ERROR LOAD DATA PRODUKSI DETAIL: ' : '';
                    foreach($temp->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                    \Yii::$app->session->setFlash('error', $message);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'temp' => $temp,
        ]);
    }

    /**
     * Deletes an existing MasterOrder model.
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
            $connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
            try{
                $model->status = 0;
                if($model->save()){
                    foreach($model->details as $detail){
                        $detail->status = 0;
                        if(!$detail->save()){
                            $success = false;
                            $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL DATA ORDER: ' : '';
                            foreach($detail->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE DATA ORDER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS DELETE ORDER.';
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
        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterOrder::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListItem($q)
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['concat(a.code, "-", a.name) as text', 'a.code id','a.*', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.status'=>1])
            ->andWhere('a.code LIKE "%'.$q.'%" OR a.name LIKE "%'.$q.'%"')
            ->limit(10)
            ->asArray()
            ->all();
        return json_encode(['results'=>$model]);
    }

    public function actionTemp()
    {
        $temps = TempMasterOrderDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $tempsProduksi = TempMasterOrderProduksiDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $total_biaya=0;
        foreach($tempsProduksi as $temp){
            $total_biaya += $temp->total_biaya;
        }
        $biaya = MasterBiayaProduksi::findAll(['status'=>1]);
        $model =  $this->renderAjax('_temp', [
            'temps'=>$temps,
            'biaya' => $biaya,
            'tempsProduksi' => $tempsProduksi,
        ]);
        return json_encode(['model'=>$model, 'total_biaya'=>number_format($total_biaya)]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempMasterOrderDetail::find()->where(['id'=>$id])->asArray()->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('TempMasterOrderDetail');
            $temp = new TempMasterOrderDetail();
            $temp->attributes = (array)$data;
            $temp->attributes = ($temp->item) ? $temp->item->attributes : '';
            if(!empty($request->post('MasterOrder')['code'])){
                $temp->order_code = $request->post('MasterOrder')['code'];
            }
            $temp->urutan = $temp->count +1;
            $jumlahProses = $temp->jumlahProses();
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
            $data = $request->post('TempMasterOrderDetail');
            $temp = $this->findTemp($data['id']);
            $temp->attributes = (array)$data;
            $temp->attributes = ($temp->item) ? $temp->item->attributes : '';
            $jumlahProses = $temp->jumlahProses();
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

    public function actionCreateTempProduksi()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $materialItem = MasterMaterialItem::findOne(['code'=>$request->post('item'), 'status'=>1]);
            $biayaProduksi = MasterBiayaProduksi::findOne(['code'=>$request->post('biaya'), 'status'=>1]);
            $temp = new TempMasterOrderProduksiDetail();
            $temp->attributes = $materialItem->attributes;
            $temp->attributes = $biayaProduksi->attributes;
            $temp->biaya_produksi_code = $biayaProduksi->code;
            $temp->item_code = $materialItem->code;
            $temp->total_biaya = $temp->totalBiaya();
            if(!empty($request->post('MasterOrder')['code'])){
                $temp->order_code = $request->post('MasterOrder')['code'];
            }
            $temp->urutan = $temp->count +1;
            $temp->user_id = \Yii::$app->user->id;
            if($temp->save()){
                $message = 'CREATE DETAIL PROSES SUCCESSFULLY';
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

    public function actionDeleteTempProduksi($id)
    {
        $success = true;
        $message = '';
        $temp = $this->findTempProduksi($id);
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
                $message = 'DELETE DETAIL PROSES SUCCESSFULLY';
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
        $temp = TempMasterOrderDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findTempProduksi($id)
    {
        $temp = TempMasterOrderProduksiDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempMasterOrderDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempMasterOrderDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_master_order_detail AUTO_INCREMENT=1')->query();
        }

        TempMasterOrderProduksiDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $tempProduksi = TempMasterOrderProduksiDetail::find()->all();
        if(empty($tempProduksi)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_master_order_produksi_detail AUTO_INCREMENT=1')->query();
        }
    }
}
