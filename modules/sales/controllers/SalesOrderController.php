<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\master\models\MasterOrder;
use app\modules\master\models\MasterPerson;
use app\modules\sales\models\SalesOrder;
use app\modules\sales\models\SalesOrderDetail;
use app\modules\sales\models\SalesOrderProdukDetail;
use app\modules\sales\models\TempSalesOrderDetail;
use app\modules\sales\models\TempSalesOrderProduksiDetail;
use app\modules\sales\models\SalesOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SalesOrderController implements the CRUD actions for SalesOrder model.
 */
class SalesOrderController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-order', 'temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'update-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order')),
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
     * Lists all SalesOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalesOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SalesOrder model.
     * @param string $no_so No So
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_so)
    {
        return $this->render('view', [
            'model' => $this->findModel($no_so),
        ]);
    }

    /**
     * Creates a new SalesOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $customer = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_CUSTOMER'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $outsourcing = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_OUTSOURCE'], 'status' => 1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = new SalesOrder();
        $temp = new TempSalesOrderDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->no_so = $model->generateCode();
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new SalesOrderDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->no_so = $model->no_so;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE SALES ORDER DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: DETAIL IS EMPTY.';
                        }

                        if(count($model->tempsProduksi()) > 0){
                            foreach($model->tempsProduksi() as $temp){
                                $detail = new SalesOrderProduksiDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->no_so = $model->no_so;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE SALES ORDER PRODUKSI DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: PROSES PRODUKSI IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE SALES ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_so.'] SUCCESS CREATE SALES ORDER.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_so' => $model->no_so]);
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
            'customer' => $customer,
            'outsourcing' => $outsourcing,
        ]);
    }

    /**
     * Updates an existing SalesOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_so No So
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_so)
    {
        $customer = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_CUSTOMER'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $outsourcing = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_OUTSOURCE'], 'status' => 1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = $this->findModel($code);
        $temp = new TempSalesOrderDetail();
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
                                $detail = new SalesOrderDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE SALES ORDER DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE SALES ORDER: DETAIL IS EMPTY.';
                        }

                        if(count($model->tempsProduksi) > 0){
                            foreach($model->detailsProduksi as $empty)
                                $empty->delete();
                            foreach($model->tempsProduksi as $temp){
                                $detail = new SalesOrderProduksiDetail();
                                $detail->attributes = $temp->attributes;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR UPDATE SALES ORDER PRODUKSI DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE SALES ORDER: PROSES PRODUKSI IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE SALES ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS UPDATE SALES ORDER.';
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
                $temp = new TempSalesOrderDetail();
                $temp->attributes = $detail->attributes;
                $temp->user_id = \Yii::$app->user->id;
                if(!$temp->save()){
                    $message = (count($temp->errors) > 0) ? 'ERROR LOAD SALES ORDER DETAIL: ' : '';
                    foreach($temp->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                    \Yii::$app->session->setFlash('error', $message);
                }
            }
            foreach($model->detailsProduksi as $detail){
                $tempProduksi = new TempSalesOrderProduksiDetail();
                $tempProduksi->attributes = $detail->attributes;
                $tempProduksi->user_id = \Yii::$app->user->id;
                if(!$tempProduksi->save()){
                    $message = (count($tempProduksi->errors) > 0) ? 'ERROR LOAD SALES ORDER PRODUKSI DETAIL: ' : '';
                    foreach($tempProduksi->errors as $error => $value){
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
            'customer' => $customer,
            'outsourcing' => $outsourcing,
        ]);
    }

    /**
     * Deletes an existing SalesOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_so No So
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_so)
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
                            $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL SALES ORDER: ' : '';
                            foreach($detail->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                    foreach($model->detailsProduksi as $detailProduksi){
                        $detailProduksi->status = 0;
                        if(!$detailProduksi->save()){
                            $success = false;
                            $message = (count($detailProduksi->errors) > 0) ? 'ERROR DELETE DETAIL PRODUKSI SALES ORDER: ' : '';
                            foreach($detailProduksi->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE SALES ORDER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS DELETE SALES ORDER.';
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

    public function actionListOrder($q)
    {
        $model = MasterOrder::find()
            ->select(['*', 'name as text', 'code id'])
            ->where(['status'=>1])
            ->andWhere('code LIKE "%'.$q.'%" OR name LIKE "%'.$q.'%"')
            ->limit(10)
            ->asArray()
            ->all();
        return json_encode(['results'=>$model]);
    }

    public function actionTemp()
    {
        $temps = TempSalesOrderDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $total_order=0;
        foreach($temps as $temp){
            foreach($temp->detailsProduksi as $val){
                $total_order += $val->total_order;
            }
        }
        $biaya = MasterBiayaProduksi::findAll(['status'=>1]);
        $model =  $this->renderAjax('_temp', [
            'temps'=>$temps,
            'biaya' => $biaya,
        ]);
        return json_encode(['model'=>$model, 'total_order'=>number_format($total_order)]);
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

    /**
     * Finds the SalesOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_so No So
     * @return SalesOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_so)
    {
        if (($model = SalesOrder::findOne($no_so)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findTemp($id)
    {
        $temp = TempSalesOrderDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findTempProduksi($id)
    {
        $temp = TempSalesOrderProduksiDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempSalesOrderDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempSalesOrderDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_sales_order_detail AUTO_INCREMENT=1')->query();
        }

        TempSalesOrderProduksiDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $tempProduksi = TempSalesOrderProduksiDetail::find()->all();
        if(empty($tempProduksi)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_sales_order_produksi_detail AUTO_INCREMENT=1')->query();
        }
    }
}
