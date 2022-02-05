<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMaterialItemPricelist;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use app\modules\sales\models\SalesOrder;
use app\modules\sales\models\SalesOrderDetail;
use app\modules\sales\models\SalesOrderItem;
use app\modules\sales\models\SalesOrderSearch;
use app\modules\sales\models\TempSalesOrderDetail;
use app\modules\sales\models\TempSalesOrderItem;
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
                            'actions' => [
                                'index', 'view', 'create', 'update', 'delete', 
                                'list-item', 'autocomplete', 'search', 'item', 'temp',
                                'create-temp', 'delete-temp-item', 'delete-temp-detail',
                                'list-proses-produksi',
                            ],
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
        $typeSatuan = MasterSatuan::find()
            ->select(['master_satuan.name'])
            ->leftJoin('master_kode', 'master_kode.code = master_satuan.type_satuan')
            ->where(['master_kode.value'=>\Yii::$app->params['TYPE_SATUAN_PRODUKSI'], 'master_satuan.status'=>1])
            ->indexBy('master_satuan.code')
            ->column();

        $success = true;
        $message = '';
        $model = new SalesOrder();
        $tempDetail = new TempSalesOrderDetail();
        $tempItem = new TempSalesOrderItem();
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->attributes = $model->attributes;
                    $model->code = $model->generateCode();
                    if($model->save()){
                        if(count($model->itemTemps()) > 0){
                            foreach($model->itemTemps() as $temp){
                                $salesItem = new SalesOrderItem();
                                $salesItem->attributes = $temp->attributes;
                                $salesItem->code = $model->code;
                                if(!$salesItem->save()){
                                    $success = false;
                                    $message = (count($salesItem->errors) > 0) ? 'ERROR CREATE SALES ORDER ITEM: ' : '';
                                    foreach($salesItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: ITEM IS EMPTY.';
                        }

                        if(count($model->detailTemps()) > 0){
                            foreach($model->detailTemps() as $temp){
                                $salesDetail = new SalesOrderDetail();
                                $salesDetail->attributes = $temp->attributes;
                                $salesDetail->code = $model->code;
                                if(!$salesDetail->save()){
                                    $success = false;
                                    $message = (count($salesDetail->errors) > 0) ? 'ERROR CREATE SALES ORDER DETAIL: ' : '';
                                    foreach($salesDetail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: DETAIL IS EMPTY.';
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
                        $message = '['.$model->code.'] SUCCESS CREATE SALES ORDER.';
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
            $model->loadDefaultValues();
            $this->emptyTemp();
        }

        return $this->render('create', [
            'model' => $model,
            'customer' => $customer,
            'tempDetail' => $tempDetail,
            'tempItem' => $tempItem,
            'typeSatuan' => $typeSatuan,
        ]);
    }

    /**
     * Updates an existing SalesOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $customer = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_CUSTOMER'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $typeSatuan = MasterSatuan::find()
            ->select(['master_satuan.name'])
            ->leftJoin('master_kode', 'master_kode.code = master_satuan.type_satuan')
            ->where(['master_kode.value'=>\Yii::$app->params['TYPE_SATUAN_PRODUKSI'], 'master_satuan.status'=>1])
            ->indexBy('master_satuan.code')
            ->column();
        
        $success = true;
        $message = '';
        $model = $this->findModel($code);
        $tempDetail = new TempSalesOrderDetail();
        $tempItem = new TempSalesOrderItem();
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->attributes = $model->attributes;
                    if($model->save()){
                        if(count($model->itemTemps) > 0){
                            foreach($model->items as $empty)
                                $empty->delete();
                            foreach($model->itemTemps as $temp){
                                $salesItem = new SalesOrderItem();
                                $salesItem->attributes = $temp->attributes;
                                if(!$salesItem->save()){
                                    $success = false;
                                    $message = (count($salesItem->errors) > 0) ? 'ERROR UPDATE SALES ORDER ITEM: ' : '';
                                    foreach($salesItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: ITEM IS EMPTY.';
                        }

                        if(count($model->detailTemps) > 0){
                            foreach($model->details as $empty)
                                $empty->delete();
                            foreach($model->detailTemps as $temp){
                                $salesDetail = new SalesOrderDetail();
                                $salesDetail->attributes = $temp->attributes;
                                if(!$salesDetail->save()){
                                    $success = false;
                                    $message = (count($salesDetail->errors) > 0) ? 'ERROR UPDATE SALES ORDER DETAIL: ' : '';
                                    foreach($salesDetail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: DETAIL IS EMPTY.';
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
            if($model->post == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Proses SPK.');
                return $this->redirect(['index']);
            }else{
                $this->emptyTemp();
                foreach($model->items as $detail){
                    $tempItem = new TempSalesOrderItem();
                    $tempItem->attributes = $detail->attributes;
                    $tempItem->user_id = \Yii::$app->user->id;
                    if(!$tempItem->save()){
                        $message = (count($tempItem->errors) > 0) ? 'ERROR LOAD SALES ORDER ITEM: ' : '';
                        foreach($tempItem->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                        \Yii::$app->session->setFlash('error', $message);
                    }
                }
                foreach($model->details as $detail){
                    $tempDetail = new TempSalesOrderDetail();
                    $tempDetail->attributes = $detail->attributes;
                    $tempDetail->user_id = \Yii::$app->user->id;
                    if(!$tempDetail->save()){
                        $message = (count($tempDetail->errors) > 0) ? 'ERROR LOAD SALES ORDER DETAIL: ' : '';
                        foreach($tempDetail->errors as $error => $value){
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
            'customer' => $customer,
            'tempDetail' => $tempDetail,
            'tempItem' => $tempItem,
            'typeSatuan' => $typeSatuan,
        ]);
    }

    /**
     * Deletes an existing SalesOrder model.
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
                    foreach($model->items as $detail){
                        $detail->status = 0;
                        if(!$detail->save()){
                            $success = false;
                            $message = (count($detail->errors) > 0) ? 'ERROR DELETE ITEM SALES ORDER: ' : '';
                            foreach($detail->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
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
                ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_item', ['model'=>$model])]);
    }

    public function actionItem()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.code as item_code', 'a.name as item_name', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
            ->asArray()
            ->one();
        return json_encode($model);
    }

    public function actionTemp()
    {
        $tempItemsMaterial = TempSalesOrderItem::findAll(['type_code' => '007', 'user_id'=> \Yii::$app->user->id]);
        $tempItemsNonMaterial = TempSalesOrderItem::find()
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere('type_code <> "007"')
            ->all();
        $model = $this->renderAjax('_temp',[
            'tempItemsMaterial'=>$tempItemsMaterial,
            'tempItemsNonMaterial'=>$tempItemsNonMaterial]);
        return json_encode(['model'=>$model]);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $dataHeader = $request->post('SalesOrder');
                if(!empty($dataHeader['code'])){
                    $code = $dataHeader['code'];
                }else{
                    $code = 'tmp';
                }
                // TEMP ITEM ATTRIBUTES
                $dataItem = $request->post('TempSalesOrderItem');
                $tempItem = new TempSalesOrderItem();
                $tempItem->attributes = (array)$dataItem;
                $tempItem->attributes = $tempItem->item->attributes;
                if(isset($tempItem->itemPricelist)){
                    $tempItem->attributes = $tempItem->itemPricelist->attributes;
                    $tempItem->code = $code;
                    $tempItem->urutan = $tempItem->countTemp +1;
                    $tempItem->total_order = $tempItem->totalOrder;
                    $tempItem->user_id = \Yii::$app->user->id;

                    // TEMP DETAIL ATTRIBUTES
                    $dataDetail = $request->post('TempSalesOrderDetail');
                    $tempDetail = new TempSalesOrderDetail();
                    $tempDetail->attributes = (array)$dataDetail;
                    $tempDetail->code = $code;
                    $tempDetail->item_code = $tempItem->item_code;
                    $tempDetail->urutan = $tempDetail->countTemp +1;
                    $jumlahProses = $tempDetail->jumlahProses(
                        $dataItem['item_code'], $dataItem['qty_order_1'], $dataItem['qty_order_2']);
                    if(!$tempDetail->lembar_ikat_1)
                        $tempDetail->lembar_ikat_1 = 0;
                    if(!$tempDetail->lembar_ikat_2)
                        $tempDetail->lembar_ikat_2 = 0;
                    if(!$tempDetail->lembar_ikat_3)
                        $tempDetail->lembar_ikat_3 = 0;
                    $tempDetail->user_id = \Yii::$app->user->id;
                    // CEK ITEM
                    if(isset($tempItem->item)){
                        if(isset($tempItem->item->typeCode)){
                            if($tempItem->item->typeCode->value == \Yii::$app->params['TYPE_MATERIAL_KERTAS']){
                                if(!$tempDetail->panjang ){
                                    $success = false;
                                    $message = 'Panjang tidak boleh kosong.';
                                }else if(!$tempDetail->lebar){
                                    $success = false;
                                    $message = 'Lebar tidak boleh kosong.';
                                }else if(!$tempDetail->total_potong){
                                    $success = false;
                                    $message = 'Potong tidak boleh kosong.';
                                }else if(!$tempDetail->total_objek){
                                    $success = false;
                                    $message = 'Objek tidak boleh kosong.';
                                }else if(!$tempDetail->total_warna){
                                    $success = false;
                                    $message = 'Warna tidak boleh kosong.';
                                }else if(!$tempDetail->satuan_ikat_code){
                                    $success = false;
                                    $message = 'Satuan Ikat tidak boleh kosong.';
                                }
                            }
                            if(empty($tempItem->itemTemp)){
                                if(!$tempItem->save()){
                                    $success = false;
                                    foreach($tempItem->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                            if($tempItem->item->typeCode->value == \Yii::$app->params['TYPE_MATERIAL_KERTAS']){
                                if(!$tempDetail->save()){
                                    $success = false;
                                    foreach($tempDetail->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'Type material tidak ditemukan di data master kode.';
                        }
                    }else{
                        $success = false;
                        $message = 'Material tidak ditemukan di data master material item.';
                    }
                }else{
                    $success = false;
                    $message = 'Pricelist untuk item '.$dataItem['item_name'].' belum di setting.';
                }
                
                if($success){
                    $transaction->commit();
                    $message = 'CREATE TEMP SUCCESSFULLY';
                }else{
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteTempItem($id)
    {
        $success = true;
        $message = '';
        $temp = TempSalesOrderItem::findOne(['id'=>$id]);
        if(isset($temp)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if($temp->delete()){
                    foreach($temp->temps as $index=>$val){
                        $val->urutan = $index +1;
                        if(!$val->save()){
                            $success = false;
                            foreach($val->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                    TempSalesOrderDetail::deleteAll('code=:code and item_code=:item_code and user_id=:user_id', [
                        ':code'=>$temp->code, ':item_code'=>$temp->item_code, ':user_id'=>\Yii::$app->user->id]);
                }else{
                    $success = false;
                    foreach($temp->errors as $error => $value){
                        $message = $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
                if($success){
                    $transaction->commit();
                    $message = 'DELETE ITEM TEMP SUCCESSFULLY.';
                }else{
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }
    
    public function actionDeleteTempDetail($id)
    {
        $success = true;
        $message = '';
        $temp = TempSalesOrderDetail::findOne(['id'=>$id]);
        if(isset($temp)){
            if($temp->delete()){
                foreach($temp->temps as $index=>$val){
                    $val->urutan = $index +1;
                    if(!$val->save()){
                        $success = false;
                        foreach($val->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }
                $message = 'DELETE DETAIL TEMP SUCCESSFULLY.';
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

    public function actionListProsesProduksi()
    {
        $model = MasterBiayaProduksi::findAll(['status'=>1]);
        return json_encode(['data'=>$this->renderPartial('_list_proses', ['model'=>$model])]);
    }

    /**
     * Finds the SalesOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return SalesOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = SalesOrder::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempSalesOrderItem::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $itemTemp = TempSalesOrderItem::find()->all();
        if(empty($itemTemp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_sales_order_item AUTO_INCREMENT=1')->query();
        }

        TempSalesOrderDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $detailTemp = TempSalesOrderDetail::find()->all();
        if(empty($detailTemp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_sales_order_detail AUTO_INCREMENT=1')->query();
        }

    }
}
