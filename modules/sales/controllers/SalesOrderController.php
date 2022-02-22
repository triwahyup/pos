<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMaterialItemPricelist;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use app\modules\sales\models\SalesOrder;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderItem;
use app\modules\sales\models\SalesOrderProses;
use app\modules\sales\models\SalesOrderSearch;
use app\modules\produksi\models\SpkInternal;
use app\modules\sales\models\TempSalesOrderItem;
use app\modules\sales\models\TempSalesOrderPotong;
use app\modules\sales\models\TempSalesOrderProses;
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
                                'on-change-term-in', 'on-input-term-in', 
                                'list-proses', 'type-order',
                                'list-order', 'autocomplete-order', 'search-order', 'select-order', 
                                'list-item', 'autocomplete-item', 'search-item', 'select-item', 
                                'temp-item', 'temp-bahan', 'temp-potong', 'get-temp', 
                                'create-temp', 'update-temp', 'delete-temp', 
                                'create-proses', 'create-potong', 'delete-potong',
                                'invoice', 'post',
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
        $ekspedisi = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_EKSPEDISI'], 'status' => 1])
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
        $tempItem = new TempSalesOrderItem();
        $tempPotong = new TempSalesOrderPotong();
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->attributes = $model->attributes;
                    $model->code = $model->generateCode();
                    $totalOrder = $model->totalOrder;
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

                        if(count($model->potongTemps()) > 0){
                            foreach($model->potongTemps() as $temp){
                                $salesPotong = new SalesOrderPotong();
                                $salesPotong->attributes = $temp->attributes;
                                $salesPotong->code = $model->code;
                                if(!$salesPotong->save()){
                                    $success = false;
                                    $message = (count($salesPotong->errors) > 0) ? 'ERROR CREATE SALES ORDER POTONG: ' : '';
                                    foreach($salesPotong->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE SALES ORDER: POTONG IS EMPTY.';
                        }

                        if(count($model->prosesTemps()) >0){
                            foreach($model->prosesTemps() as $temp){
                                $salesProses = new SalesOrderProses();
                                $salesProses->attributes = $temp->attributes;
                                $salesProses->code = $model->code;
                                if(!$salesProses->save()){
                                    $success = false;
                                    $message = (count($salesProses->errors) > 0) ? 'ERROR CREATE SALES ORDER PROSES PRODUKSI: ' : '';
                                    foreach($salesProses->errors as $error => $value){
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
            'ekspedisi' => $ekspedisi,
            'tempItem' => $tempItem,
            'tempPotong' => $tempPotong,
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
        $ekspedisi = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_EKSPEDISI'], 'status' => 1])
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
        $tempItem = new TempSalesOrderItem();
        $tempPotong = new TempSalesOrderPotong();
        if($this->request->isPost){
            if($model->load($this->request->post()) && $tempItem->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->attributes = $model->attributes;
                    if(count($model->itemTemps) > 0){
                        foreach($model->items as $empty)
                            $empty->delete();
                        foreach($model->itemTemps as $temp){
                            $salesItem = new SalesOrderItem();
                            $salesItem->attributes = $temp->attributes;
                            if($salesItem->item->typeCode->value == \Yii::$app->params['TYPE_MATERIAL_KERTAS']){
                                $salesItem->qty_order_1 = $tempItem->qty_order_1;
                                $salesItem->qty_order_2 = $tempItem->qty_order_2;
                                $salesItem->total_order = $salesItem->totalOrder;
                                $salesItem->jumlah_cetak = $salesItem->jumlahCetak;
                            }
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
                        $message = 'ERROR UPDATE SALES ORDER: ITEM IS EMPTY.';
                    }

                    if(count($model->potongTemps) > 0){
                        foreach($model->potongs as $empty)
                            $empty->delete();
                        foreach($model->potongTemps as $temp){
                            $salesPotong = new SalesOrderPotong();
                            $salesPotong->attributes = $temp->attributes;
                            if(!$salesPotong->save()){
                                $success = false;
                                $message = (count($salesPotong->errors) > 0) ? 'ERROR UPDATE SALES ORDER POTONG: ' : '';
                                foreach($salesPotong->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = 'ERROR UPDATE SALES ORDER: POTONG IS EMPTY.';
                    }

                    if(count($model->prosesTemps) >0){
                        foreach($model->proses as $empty)
                            $empty->delete();
                        foreach($model->prosesTemps as $temp){
                            $salesProses = new SalesOrderProses();
                            $salesProses->attributes = $temp->attributes;
                            $totalBiaya = $salesProses->totalBiaya($salesProses);
                            if(!$salesProses->save()){
                                $success = false;
                                $message = (count($salesProses->errors) > 0) ? 'ERROR UPDATE SALES ORDER PROSES PRODUKSI: ' : '';
                                foreach($salesProses->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = 'ERROR UPDATE SALES ORDER: PROSES PRODUKSI IS EMPTY.';
                    }
                    
                    $updateTotalOrder = $model->updateTotalOrder;
                    if(!$model->save()){
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
                foreach($model->potongs as $detail){
                    $tempPotong = new TempSalesOrderPotong();
                    $tempPotong->attributes = $detail->attributes;
                    $tempPotong->user_id = \Yii::$app->user->id;
                    if(!$tempPotong->save()){
                        $message = (count($tempPotong->errors) > 0) ? 'ERROR LOAD SALES ORDER POTONG: ' : '';
                        foreach($tempPotong->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                        \Yii::$app->session->setFlash('error', $message);
                    }
                }
                foreach($model->proses as $detail){
                    $tempProses = new TempSalesOrderProses();
                    $tempProses->attributes = $detail->attributes;
                    $tempProses->user_id = \Yii::$app->user->id;
                    if(!$tempProses->save()){
                        $message = (count($tempProses->errors) > 0) ? 'ERROR LOAD SALES ORDER PROSES PRODUKSI: ' : '';
                        foreach($tempProses->errors as $error => $value){
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
            'ekspedisi' => $ekspedisi,
            'tempItem' => $tempItem,
            'tempPotong' => $tempPotong,
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
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Proses SPK.');
                    return $this->redirect(['index']);
                }else{
                    $model->status = 0;
                    if($model->save()){
                        foreach($model->items as $item){
                            $item->status = 0;
                            if(!$item->save()){
                                $success = false;
                                $message = (count($item->errors) > 0) ? 'ERROR DELETE ITEM SALES ORDER: ' : '';
                                foreach($item->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        foreach($model->potongs as $potong){
                            $potong->status = 0;
                            if(!$potong->save()){
                                $success = false;
                                $message = (count($potong->errors) > 0) ? 'ERROR DELETE POTONG SALES ORDER: ' : '';
                                foreach($potong->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        foreach($model->proses as $proses){
                            $proses->status = 0;
                            if(!$proses->save()){
                                $success = false;
                                $message = (count($proses->errors) > 0) ? 'ERROR DELETE PROSES PRODUKSI SALES ORDER: ' : '';
                                foreach($proses->errors as $error => $value){
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

    public function actionOnChangeTermIn($customer_code, $tgl_so)
    {
        $model = MasterPerson::find()->where(['code'=>$customer_code])->asArray()->one();
        $termIn = (!empty($model['term_in'])) ? $model['term_in'] : 0;
        $top = date('d-m-Y', strtotime('+'.$termIn.' days', strtotime($tgl_so)));
        $tgl_tempo = '<i class="text-muted font-size-10">Tgl. Jatuh Tempo Pembayaran: '.$top.'</i>';
        return json_encode(['term_in'=>$termIn, 'tgl_tempo'=>$tgl_tempo]);
    }

    public function actionOnInputTermIn($tgl_so, $term_in)
    {
        $termIn = (!empty($term_in)) ? $term_in : 0;
        $top = date('d-m-Y', strtotime('+'.$termIn.' days', strtotime($tgl_so)));
        $tgl_tempo = '<i class="text-muted font-size-10">Tgl. Jatuh Tempo Pembayaran: '.$top.'</i>';
        return json_encode(['tgl_tempo'=>$tgl_tempo]);
    }

    public function actionTypeOrder($type)
    {
        $item = MasterMaterialItem::findOne(['material_code'=>\Yii::$app->params['TYPE_PRODUK_JASA']]);
        $data = ['item_name' => null, 'item_code' => null, 'qty_order_1' => 20, 'qty_order_2' => 0];
        if($type == 2){
            $data = ['item_name' => $item->name, 'item_code' => $item->code, 'qty_order_1' => 0, 'qty_order_2' => 0];
        }
        return json_encode($data);
    }

    public function actionListOrder()
    {
        $model = SalesOrder::find()
            ->alias('a')
            ->leftJoin('master_person b', 'b.code = a.customer_code')
            ->where(['customer_code'=>$_POST['customer_code'], 'a.status'=>1])
            ->orderBy(['a.code'=>SORT_ASC])
            ->limit(10)
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_order', [
            'model'=>$model])
        ]);
    }

    public function actionAutocompleteOrder()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = SalesOrder::find()
                ->alias('a')
                ->select(['a.code', 'a.name as label'])
                ->leftJoin('master_person b', 'b.code = a.customer_code')
                ->where(['a.status'=>1])
                ->andWhere('a.name LIKE "%'.$_POST['search'].'%"')
                ->orderBy(['a.code'=>SORT_ASC])
                ->asArray()
                ->limit(10)
                ->all();
        }
        return  json_encode($model);
    }

    public function actionSearchOrder()
    {
        $model = [];
        if(isset($_POST['code'])){
            $model = SalesOrder::find()
                ->alias('a')
                ->leftJoin('master_person b', 'b.code = a.customer_code')
                ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
                ->orderBy(['a.code'=>SORT_ASC])
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_order', [
            'model'=>$model])
        ]);
    }

    public function actionSelectOrder()
    {
        $model = SalesOrder::find()->where(['code'=>$_POST['code'], 'status'=>1])->asArray()->one();
        $model['tgl_so'] = date('d-m-Y', strtotime($model['tgl_so']));
        $model['tgl_po'] = date('d-m-Y', strtotime($model['tgl_po']));
        $model['deadline'] = date('d-m-Y', strtotime($model['deadline']));

        $this->emptyTemp();
        $salesOrder = $this->findModel($model['code']);
        $tempDetail = [];
        foreach($salesOrder->items as $detail){
            $tempItem = new TempSalesOrderItem();
            $tempItem->attributes = $detail->attributes;
            $tempItem->code = 'tmp';
            $tempItem->user_id = \Yii::$app->user->id;
            if(!$tempItem->save()){
                $message = (count($tempItem->errors) > 0) ? 'ERROR LOAD SALES ORDER ITEM: ' : '';
                foreach($tempItem->errors as $error => $value){
                    $message .= strtoupper($value[0].', ');
                }
                $message = substr($message, 0, -2);
                \Yii::$app->session->setFlash('error', $message);
            }
            $tempDetail = ['qty_order_1' => $tempItem->itemMaterial->qty_order_1];
        }
        foreach($salesOrder->potongs as $detail){
            $tempPotong = new TempSalesOrderPotong();
            $tempPotong->attributes = $detail->attributes;
            $tempPotong->code = 'tmp';
            $tempPotong->user_id = \Yii::$app->user->id;
            if(!$tempPotong->save()){
                $message = (count($tempPotong->errors) > 0) ? 'ERROR LOAD SALES ORDER POTONG: ' : '';
                foreach($tempPotong->errors as $error => $value){
                    $message .= strtoupper($value[0].', ');
                }
                $message = substr($message, 0, -2);
                \Yii::$app->session->setFlash('error', $message);
            }
        }
        foreach($salesOrder->proses as $detail){
            $tempProses = new TempSalesOrderProses();
            $tempProses->attributes = $detail->attributes;
            $tempProses->code = 'tmp';
            $tempProses->user_id = \Yii::$app->user->id;
            if(!$tempProses->save()){
                $message = (count($tempProses->errors) > 0) ? 'ERROR LOAD SALES ORDER PROSES PRODUKSI: ' : '';
                foreach($tempProses->errors as $error => $value){
                    $message .= strtoupper($value[0].', ');
                }
                $message = substr($message, 0, -2);
                \Yii::$app->session->setFlash('error', $message);
            }
        }

        $model['qty_order_1'] = $tempDetail['qty_order_1'];
        return json_encode($model);
    }
    
    public function actionListItem($type)
    {
        $andWhere = '';
        if($type == 'item')
            $andWhere = 'value = "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"';
        if($type == 'bahan')
            $andWhere = 'value <> "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"';

        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->leftJoin('master_kode c', 'c.code = a.type_code')
            ->where(['a.status'=>1])
            ->andWhere($andWhere)
            ->orderBy(['a.code'=>SORT_ASC])
            ->limit(10)
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_item', [
            'model'=>$model, 'type'=>$type])
        ]);
    }

    public function actionAutocompleteItem()
    {
        $model = [];
        if(isset($_POST['search'])){
            $andWhere = '';
            if($_POST['type'] == 'item')
                $andWhere = 'value = "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"';
            if($_POST['type'] == 'bahan')
                $andWhere = 'value <> "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"';

            $model = MasterMaterialItem::find()
                ->alias('a')
                ->select(['a.code', 'concat(a.code,"-",a.name) label'])
                ->leftJoin('master_kode b', 'b.code = a.type_code')
                ->where(['a.status'=>1])
                ->andWhere('concat(a.code,"-", a.name) LIKE "%'.$_POST['search'].'%"')
                ->andWhere($andWhere)
                ->asArray()
                ->limit(10)
                ->all();
        }
        return  json_encode($model);
    }

    public function actionSearchItem()
    {
        $model = [];
        if(isset($_POST['code'])){
            $andWhere = '';
            if($_POST['type'] == 'item')
                $andWhere = 'value = "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"';
            if($_POST['type'] == 'bahan')
                $andWhere = 'value <> "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"';

            $model = MasterMaterialItem::find()
                ->alias('a')
                ->select(['a.*', 'b.composite'])
                ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
                ->leftJoin('master_kode c', 'c.code = a.type_code')
                ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
                ->andWhere($andWhere)
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_item', [
            'model'=>$model, 'type'=>$_POST['type']])
        ]);
    }

    public function actionSelectItem()
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

        TempSalesOrderPotong::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $potongTemp = TempSalesOrderPotong::find()->all();
        if(empty($potongTemp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_sales_order_potong AUTO_INCREMENT=1')->query();
        }

        TempSalesOrderProses::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $prosesTemp = TempSalesOrderProses::find()->all();
        if(empty($prosesTemp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_sales_order_proses AUTO_INCREMENT=1')->query();
        }
    }

    public function actionTempItem()
    {
        $temps = TempSalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['value'=>\Yii::$app->params['TYPE_MATERIAL_KERTAS'], 'user_id'=> \Yii::$app->user->id])
            ->all();
        $model = $this->renderAjax('_temp_item', [
            'temps'=>$temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionTempBahan()
    {
        $temps = TempSalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_MATERIAL_KERTAS'].'"')
            ->all();
        $model = $this->renderAjax('_temp_bahan', [
            'temps' => $temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionTempPotong()
    {
        $temps = TempSalesOrderPotong::findAll(['user_id' => \Yii::$app->user->id]);
        $tempsProses = TempSalesOrderProses::findAll(['user_id' => \Yii::$app->user->id]);
        $model = $this->renderAjax('_temp_potong', [
            'temps' => $temps,
            'tempsProses' => $tempsProses]);
        return json_encode(['model'=>$model]);
    }

    public function actionCreatePotong()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $dataHeader = $request->post('SalesOrder');
            $code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
            $dataItem = $request->post('TempSalesOrderItem');
            $tempItem = new TempSalesOrderItem();
            $tempItem->attributes = (array)$dataItem;
            if(!empty($tempItem->item_name) && !empty($tempItem->item_code)){
                if(!empty($tempItem->total_potong)){
                    $dataPotong = $request->post('TempSalesOrderPotong');
                    if(empty($dataPotong['panjang'])){
                        $success = false;
                        $message = 'Panjang tidak boleh kosong.';
                    }else if(empty($dataPotong['lebar'])){
                        $success = false;
                        $message = 'Lebar tidak boleh kosong.';
                    }else if(empty($dataPotong['objek'])){
                        $success = false;
                        $message = 'Objek tidak boleh kosong.';
                    }else{
                        $tempPotong = new TempSalesOrderPotong();
                        $tempPotong->attributes = (array)$dataPotong;
                        $tempPotong->code = $code;
                        $tempPotong->item_code = $tempItem->item_code;
                        $tempPotong->urutan = $tempPotong->countTemp +1;
                        $tempPotong->user_id = \Yii::$app->user->id;
                        $tempPotong->total_objek = $tempItem->jumlahCetak * $tempPotong->objek;
                        if($tempPotong->countTemp < $tempItem->total_potong){
                            if(!$tempPotong->save()){
                                $success = false;
                                foreach($tempPotong->errors as $error => $value){
                                    $message = $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'Maksimal Detail Potong '.$dataItem['total_potong'].'.';
                        }
                    }
                }else{
                    $success = false;
                    $message = 'Total Potong tidak boleh kosong.';
                }
            }else{
                $success = false;
                $message = 'Material tidak boleh kosong.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeletePotong($id)
    {
        $success = true;
        $message = '';
        $temp = TempSalesOrderPotong::findOne(['id'=>$id]);
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

    public function actionGetTemp($id)
    {
        $temp = TempSalesOrderItem::find()
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
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $dataHeader = $request->post('SalesOrder');
                $code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
                // TEMP ITEM ATTRIBUTES
                $dataItem = $request->post('TempSalesOrderItem');
                $tempItem = new TempSalesOrderItem();
                $tempItem->attributes = (array)$dataItem;
                if(isset($tempItem->itemBahan)){
                    $tempItem->item_code = $tempItem->bahan_item_code;
                    $tempItem->qty_order_1 = $tempItem->bahan_qty_order_1;
                    $tempItem->qty_order_2 = $tempItem->bahan_qty_order_2;
                    $tempItem->total_potong = null;
                    $tempItem->total_warna = null;
                    $tempItem->satuan_ikat_code = null;
                    $tempItem->lembar_ikat_1 = null;
                    $tempItem->lembar_ikat_2 = null;
                    $tempItem->lembar_ikat_3 = null;
                    $tempItem->lembar_ikat_um_1 = null;
                    $tempItem->lembar_ikat_um_2 = null;
                    $tempItem->lembar_ikat_um_3 = null;
                }
                $tempItem->attributes = $tempItem->item->attributes;
                if(isset($tempItem->itemPricelist)){
                    $tempItem->attributes = $tempItem->itemPricelist->attributes;
                    $tempItem->code = $code;
                    $tempItem->urutan = $tempItem->countTemp +1;
                    $tempItem->total_order = $tempItem->totalOrder;
                    $tempItem->user_id = \Yii::$app->user->id;
                    if($tempItem->item->typeCode->value == \Yii::$app->params['TYPE_MATERIAL_KERTAS']){
                        if(!$tempItem->total_potong){
                            $success = false;
                            $message = 'Total potong tidak boleh kosong.';
                        }else if(!$tempItem->total_warna){
                            $success = false;
                            $message = 'Total warna tidak boleh kosong.';
                        }else if(!$tempItem->satuan_ikat_code){
                            $success = false;
                            $message = 'Satuan Ikat tidak boleh kosong.';
                        }else{
                            if(!$tempItem->lembar_ikat_1) $tempItem->lembar_ikat_1 = 0;
                            if(!$tempItem->lembar_ikat_2) $tempItem->lembar_ikat_2 = 0;
                            if(!$tempItem->lembar_ikat_3) $tempItem->lembar_ikat_3 = 0;
                        }
                        $tempItem->jumlah_cetak = $tempItem->jumlahCetak;
                    }
                }else{
                    $success = false;
                    if(isset($tempItem->itemBahan)){
                        $itemName = $dataItem['bahan_item_name'];
                    }else{
                        $itemName = $dataItem['item_name'];
                    }
                    $message = 'Pricelist untuk item '.$itemName.' belum di setting.';
                }
                
                if($success){
                    if(empty($tempItem->itemTemp)){
                        if(!$tempItem->save()){
                            $success = false;
                            foreach($tempItem->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'ITEM SUDAH ADA.';
                    }
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

    public function actionUpdateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $dataItem = $request->post('TempSalesOrderItem');
                if(isset($dataItem['id'])){
                    $tempItem = TempSalesOrderItem::findOne(['id'=>$dataItem['id']]);
                    $tempCode = $dataItem['code'];
                }else{
                    $tempItem = TempSalesOrderItem::find()->where(['code'=>'tmp'])->orderBy(['id'=>SORT_ASC])->one();
                    $tempCode = 'tmp';
                }
                
                $itemCode = $tempItem->item_code;
                $tempItem->item_code = $dataItem['item_code'];
                if($tempItem->save()){
                    $tempsPotong = TempSalesOrderPotong::findAll(['code'=>$tempCode, 'item_code'=>$itemCode]);
                    foreach($tempsPotong as $temp){
                        $temp->item_code = $tempItem->item_code;
                        if(!$temp->save()){
                            $success = false;
                            foreach($tempItem->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                    $tempsProses = TempSalesOrderProses::findAll(['code'=>$tempCode, 'item_code'=>$itemCode]);
                    foreach($tempsProses as $temp){
                        $temp->item_code = $tempItem->item_code;
                        if(!$temp->save()){
                            $success = false;
                            foreach($tempItem->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }else{
                    $success = false;
                    foreach($tempItem->errors as $error => $value){
                        $message = $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'UPDATE TEMP SUCCESSFULLY';
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

    public function actionDeleteTemp($id)
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
                    TempSalesOrderPotong::deleteAll('code=:code and item_code=:item_code and user_id=:user_id', [
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

    public function actionListProses($id)
    {
        $data = [];
        $model = MasterBiayaProduksi::findAll(['status'=>1]);
        $tempItem = TempSalesOrderItem::findOne(['id'=>$id]);
        foreach($model as $val){
            $data[$val->code] = [
                'name' => $val->name,
                'biaya_code' => $val->code,
                'code' => $tempItem->code,
                'item_code' => $tempItem->item_code,
            ];
        }
        if(count($tempItem->prosesTemps) > 0){
            foreach($tempItem->prosesTemps as $val){
                $data[$val->biaya_code] = [
                    'id' => $val->id,
                    'name' => (isset($val->biayaProduksi)) ? $val->biayaProduksi->name : '-',
                    'biaya_code' => $val->biaya_code,
                    'code' => $val->code,
                    'item_code' => $val->item_code,
                    'keterangan' => $val->keterangan,
                ];
            }
        }
        return json_encode(['data'=>$this->renderPartial('_list_proses', [
            'data'=>$data,
            'tempItem'=>$tempItem])
        ]);
    }

    public function actionCreateProses()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $dataProses = $request->post('TempSalesOrderProses');
            if(!empty($dataProses['biaya_code'])){
                TempSalesOrderProses::deleteAll('code=:code and item_code=:item_code and user_id=:user_id', [
                    ':code'=>$dataProses['code'], ':item_code'=>$dataProses['item_code'], ':user_id'=>\Yii::$app->user->id]);
                foreach($dataProses['biaya_code'] as $val){
                    $tempProses = new TempSalesOrderProses();
                    $biayaProduksi = $tempProses->biayaProduksi($val);
                    $tempProses->attributes = $biayaProduksi->attributes;
                    $tempProses->attributes = (array)$dataProses;
                    $tempProses->biaya_code = $val;
                    $tempProses->keterangan = $tempProses['keterangan'][$val];
                    $tempProses->user_id = \Yii::$app->user->id;
                    $totalBiaya = $tempProses->totalBiaya($dataProses);
                    if(!$tempProses->save()){
                        $success = false;
                        foreach($tempProses->errors as $error => $value){
                            $message = $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }
            }else{
                $success = false;
                $message = 'Pilih salah satu proses.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionInvoice($code)
    {
        return $this->render('_invoice', [
            'model' => $this->findModel($code),
        ]);
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
                                $stockTransaction->no_document = $model->code;
                                $stockTransaction->tgl_document = $model->tgl_so;
                                $stockTransaction->type_document = "SALES ORDER";
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
                    // PROSES KURANG STOK UP PRODUKSI (%)
                    if(!empty($model->up_produksi) || $model->up_produksi != 0){
                        $upproduksi = $stock * ($model->up_produksi/100);
                        if($stockItem->onhand > $upproduksi){
                            $stockItem->onhand = $stockItem->onhand - $upproduksi;
                            $stockItem->onsales = $stockItem->onsales + $upproduksi;
                            if(!$stockItem->save()){
                                $success = false;
                                $message = (count($stockItem->errors) > 0) ? 'ERROR UPDATE STOCK ITEM (UP PRODUKSI): ' : '';
                                foreach($stockItem->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }

                            $stockTransaction = new InventoryStockTransaction();
                            $stockTransaction->attributes = $stockItem->attributes;
                            $stockTransaction->no_document = $model->code;
                            $stockTransaction->tgl_document = $model->tgl_so;
                            $stockTransaction->type_document = "SALES ORDER";
                            $stockTransaction->status_document = "OUT (UP PRODUKSI ".$model->up_produksi." %)";
                            $stockTransaction->qty_out = $upproduksi;
                            if(!$stockTransaction->save()){
                                $success = false;
                                $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION (UP PRODUKSI): ' : '';
                                foreach($stockTransaction->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'SISA STOCK ITEM '.$stockItem->item_code.' TIDAK MENCUKUPI. SISA '.$stockItem->onhand;
                        }
                    }
                    // PROSES SIMPAN SPK
                    $spkInternal = new SpkInternal();
                    $spkInternal->attributes = $model->attributes;
                    $spkInternal->no_so = $model->code;
                    $spkInternal->no_spk = $spkInternal->generateCode();
                    $spkInternal->tgl_spk = date('Y-m-d');
                    if(!$spkInternal->save()){
                        $success = false;
                        $message = (count($spkInternal->errors) > 0) ? 'ERROR CREATE SPK: ' : '';
                        foreach($spkInternal->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST SALES ORDER TO SPK: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->code.'] SUCCESS POST SALES ORDER TO SPK.';
                    $transaction->commit();
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
            $message = 'Data Sales Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'code' => $model->code]);
    }
}