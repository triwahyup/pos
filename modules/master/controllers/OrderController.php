<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterOrder;
use app\modules\master\models\MasterOrderDetail;
use app\modules\master\models\MasterOrderDetailProduksi;
use app\modules\master\models\TempMasterOrderDetail;
use app\modules\master\models\TempMasterOrderDetailProduksi;
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
                            'actions' => ['index', 'view', 'list-item', 'temp', 'get-temp', 'search', 'item', 'autocomplete'],
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
                                $detail = new MasterOrderDetailProduksi();
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
                                $detail = new MasterOrderDetailProduksi();
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
                $tempProduksi = new TempMasterOrderDetailProduksi();
                $tempProduksi->attributes = $detail->attributes;
                $tempProduksi->user_id = \Yii::$app->user->id;
                if(!$tempProduksi->save()){
                    $message = (count($tempProduksi->errors) > 0) ? 'ERROR LOAD DATA PRODUKSI DETAIL: ' : '';
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
                    foreach($model->detailsProduksi as $detailProduksi){
                        $detailProduksi->status = 0;
                        if(!$detailProduksi->save()){
                            $success = false;
                            $message = (count($detailProduksi->errors) > 0) ? 'ERROR DELETE DETAIL PRODUKSI DATA ORDER: ' : '';
                            foreach($detailProduksi->errors as $error => $value){
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

    public function actionListItem()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.type_code'=>\Yii::$app->params['MATERIAL_KERTAS_CODE'], 'a.status'=>1])
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
                ->where(['type_code'=>\Yii::$app->params['MATERIAL_KERTAS_CODE'], 'status'=>1])
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
            ->select(['a.*', 'a.code as item_code', 'a.name as item_name', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
            ->asArray()
            ->one();
        return json_encode($model);
    }

    public function actionTemp()
    {
        $temps = TempMasterOrderDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $total_order=0;
        $total_biaya=0;
        foreach($temps as $temp){
            $total_order += $temp->total_order;
            foreach($temp->detailsProduksi as $val){
                $total_biaya += $val->total_biaya;
            }
        }
        $grand_total = $total_order+$total_biaya;
        
        $biaya = MasterBiayaProduksi::findAll(['status'=>1]);
        $model =  $this->renderAjax('_temp', ['temps'=>$temps, 'biaya' => $biaya]);
        $temps_produksi = TempMasterOrderDetailProduksi::find()->where(['user_id'=> \Yii::$app->user->id])->asArray()->all();
        return json_encode([
            'model'=>$model,
            'total_order'=>number_format($total_order),
            'total_biaya'=>number_format($total_biaya),
            'grand_total'=>number_format($grand_total),
            'temps_produksi'=>$temps_produksi,
        ]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempMasterOrderDetail::find()
            ->alias('a')
            ->select(['a.*', 'b.name as item_name'])
            ->leftJoin('master_material_item b', 'b.code = a.item_code')
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('TempMasterOrderDetail');
            $checkTemp = TempMasterOrderDetail::find()
                ->where(['item_code'=>$data['item_code'], 'user_id'=> \Yii::$app->user->id])
                ->one();
            if(empty($checkTemp)){
                $temp = new TempMasterOrderDetail();
                $temp->attributes = (array)$data;
                $temp->attributes = ($temp->item) ? $temp->item->attributes : '';
                if(!empty($request->post('MasterOrder')['code'])){
                    $temp->order_code = $request->post('MasterOrder')['code'];
                }
                $temp->urutan = $temp->count +1;
                $temp->total_order = $temp->totalOrder;
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
                $success = false;
                $message = 'Item sudah ada. Silakan pilih update item untuk merubah QTY.';
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
            $temp->total_order = $temp->totalOrder;
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
            $checkTemp = $model = TempMasterOrderDetailProduksi::find()
                ->where(['biaya_produksi_code'=>$biayaProduksi->code, 'item_code'=>$materialItem->code, 'user_id'=> \Yii::$app->user->id])
                ->one();
            if(empty($checkTemp)){
                $temp = new TempMasterOrderDetailProduksi();
                $temp->attributes = $materialItem->attributes;
                $temp->attributes = $biayaProduksi->attributes;
                $temp->biaya_produksi_code = $biayaProduksi->code;
                $temp->item_code = $materialItem->code;
                $temp->total_biaya = $temp->totalBiaya();
                if(!empty($request->post('code'))){
                    $temp->order_code = $request->post('code');
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
                $success = false;
                $message = 'Proses sudah ada.';
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
        $temp = TempMasterOrderDetailProduksi::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
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

        TempMasterOrderDetailProduksi::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $tempProduksi = TempMasterOrderDetailProduksi::find()->all();
        if(empty($tempProduksi)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_master_order_detail_produksi AUTO_INCREMENT=1')->query();
        }
    }
}
