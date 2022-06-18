<?php

namespace app\modules\sales\controllers;

use app\models\DataList;
use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialPricelist;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterProses;
use app\modules\master\models\MasterSatuan;
use app\modules\sales\models\SalesOrder;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderItem;
use app\modules\sales\models\SalesOrderProses;
use app\modules\sales\models\SalesOrderSearch;
use app\modules\produksi\models\SpkOrder;
use app\modules\produksi\models\SpkOrderProses;
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
                            'actions' => ['create', 'create-temp', 'create-potong', 'create-proses'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [
                                'index', 'view', 'invoice', 'list-proses', 'type-order',
                                'on-change-term-in', 'on-input-term-in', 'on-change-up', 
                                'temp-item', 'temp-bahan', 'temp-proses', 'get-temp',
                                'list-item', 'autocomplete-item', 'search-item', 'select-item', 
                                'list-order', 'autocomplete-order', 'search-order', 'select-order'
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'post', 'update-temp', 'send-approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order[U]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp', 'delete-potong'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-order[D]')),
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
        $model = $this->findModel($code);
        $cancelOrder = false;
        $spkOrder = SpkOrder::findOne(['no_so'=>$code, 'status_produksi'=>1]);
        if(!empty($spkOrder)){
            $cancelOrder = true;
        }
        return $this->render('view', [
            'model' => $model,
            'cancelOrder' => $cancelOrder,
        ]);
    }

    /**
     * Creates a new SalesOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $dataList = DataList::setListColumn();
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

                        $totalQtyUp = 0;
                        $totalQtyOrder = 0;
                        $totalQtyKonv = 0;
                        foreach($model->itemsMaterial as $val){
                            $totalQtyUp += $val->qty_up;
                            if($model->type_qty == 1){
                                $konv = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                    0=>$val->qty_order_1, 1=>0]);
                                $totalQtyOrder += $konv;
                            }else{
                                $totalQtyOrder += $val->qty_order_2;
                            }
                            $totalQtyKonv = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                0=>$model->total_qty, 1=>0]);
                        }
                        
                        if($totalQtyOrder > $totalQtyKonv){
                            $success = false;
                            $message = 'Total qty order tidak boleh lebih dari '.$model->total_qty;
                        }
                        $model->total_qty_up = str_replace(',', '', $model->total_qty_up);
                        if($totalQtyUp > $model->total_qty_up){
                            $success = false;
                            $message = 'Total up produksi tidak boleh lebih dari '.$model->total_qty_up;
                        }
                    }else{
                        $success = false;
                        $message = 'ERROR CREATE SALES ORDER: ITEM IS EMPTY.';
                    }
                    
                    if(count($model->potongTemps()) > 0){
                        foreach($model->potongTemps() as $temp){
                            $tempPotong = TempSalesOrderPotong::findOne(['code'=>$temp->code, 'item_code'=>$temp->item_code, 'supplier_code'=>$temp->supplier_code]);
                            if(isset($tempPotong)){
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
                            }else{
                                $success = false;
                                $message = 'PROSES POTONG ITEM: '.$temp->item->name.', SUPPLIER: '.$temp->supplier->name.' MASIH KOSONG.';
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

                    $totalOrder = $model->totalOrder;
                    if(!$model->save()){
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
            $this->emptyTemp();
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'customer' => $dataList['customer'],
            'ekspedisi' => $dataList['ekspedisi'],
            'sales' => $dataList['sales'],
            'typeSatuan' => $dataList['satuan'],
            'tempItem' => $tempItem,
            'tempPotong' => $tempPotong,
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
        $success = true;
        $message = '';
        $dataList = DataList::setListColumn();
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
                            $salesItem->total_order = $salesItem->totalOrder;
                            $salesItem->qty_up = (!empty($model->up_produksi) || $model->up_produksi != 0) ? $temp->qty_up : null;
                            if(!$salesItem->save()){
                                $success = false;
                                $message = (count($salesItem->errors) > 0) ? 'ERROR UPDATE SALES ORDER ITEM: ' : '';
                                foreach($salesItem->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        
                        $totalQtyUp = 0;
                        $totalQtyOrder = 0;
                        $totalQtyKonv = 0;
                        foreach($model->itemsMaterial as $val){
                            $totalQtyUp += $val->qty_up;
                            if($model->type_qty == 1){
                                $konv = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                    0=>$val->qty_order_1, 1=>0]);
                                $totalQtyOrder += $konv;
                            }else{
                                $totalQtyOrder += $val->qty_order_2;
                            }
                            $totalQtyKonv = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                0=>$model->total_qty, 1=>0]);
                        }
                        
                        if($totalQtyOrder > $totalQtyKonv){
                            $success = false;
                            $message = 'Total qty order tidak boleh lebih dari '.$model->total_qty;
                        }else if($totalQtyOrder < $totalQtyKonv){
                            $success = false;
                            $message = 'Total qty order masih kurang.';
                        }
                        
                        $model->total_qty_up = str_replace(',', '', $model->total_qty_up);
                        if($totalQtyUp > $model->total_qty_up){
                            $success = false;
                            $message = 'Total up produksi tidak boleh lebih dari '.$model->total_qty_up;
                        }else if($totalQtyUp < $model->total_qty_up){
                            $success = false;
                            $message = 'Total qty up produksi masih kurang.';
                        }
                    }else{
                        $success = false;
                        $message = 'ERROR UPDATE SALES ORDER: ITEM IS EMPTY.';
                    }

                    if(count($model->potongTemps) > 0){
                        foreach($model->potongs as $empty)
                            $empty->delete();
                        foreach($model->potongTemps as $temp){
                            $tempPotong = TempSalesOrderPotong::findOne(['code'=>$temp->code, 'item_code'=>$temp->item_code, 'supplier_code'=>$temp->supplier_code]);
                            if(isset($tempPotong)){
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
                            }else{
                                $success = false;
                                $message = 'PROSES POTONG ITEM: '.$temp->item->name.', SUPPLIER: '.$temp->supplier->name.' MASIH KOSONG.';
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
                    
                    $totalOrder = $model->totalOrder;
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
                if($model->status == 0){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini telah di Cancel Order.');
                    return $this->redirect(['index']);
                }else{
                    $this->emptyTemp();
                    foreach($model->items as $detail){
                        $tempItem = new TempSalesOrderItem();
                        $tempItem->attributes = $detail->attributes;
                        $tempItem->item_name = $tempItem->item->name;
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
                    $tempItem->qty_order_1 = null;
                    $tempItem->item_name = null;
                }
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'customer' => $dataList['customer'],
            'ekspedisi' => $dataList['ekspedisi'],
            'sales' => $dataList['sales'],
            'typeSatuan' => $dataList['satuan'],
            'tempItem' => $tempItem,
            'tempPotong' => $tempPotong,
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
                    $spkOrder = SpkOrder::findOne(['no_so'=>$model->code, 'status_produksi'=>1]);
                    if(!empty($spkOrder)){
                        $spkOrder->status = 0;
                        if($spkOrder->save()){
                            // PROSES KEMBALIKAN STOK
                            $stock = 0;
                            foreach($model->items as $val){
                                $stockItem = $val->inventoryStock;
                                if(isset($stockItem)){
                                    $stock = $stockItem->satuanTerkecil($val->item_code, [
                                        0=>$val->qty_order_1,
                                        1=>$val->qty_order_2
                                    ]);
                                    
                                    $stockItem->attributes = $val->attributes;
                                    $stockItem->onhand = $stockItem->onhand + $stock;
                                    $stockItem->onsales = $stockItem->onsales - $stock;
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
                                    $stockTransaction->type_document = "CANCEL ORDER";
                                    $stockTransaction->status_document = "IN";
                                    $stockTransaction->qty_in = $stock;
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
                                    $message = 'STOCK ITEM '.$val->item_code.' TIDAK DITEMUKAN.';
                                }
                            }
                            // PROSES KEMBALIKAN STOK UP PRODUKSI (%)
                            $stock = 0;
                            foreach($model->itemsMaterial as $val){
                                $stockItem = $val->inventoryStock;
                                if(isset($stockItem)){
                                    $stock = $stockItem->satuanTerkecil($val->item_code, [
                                        0=>$val->qty_order_1,
                                        1=>$val->qty_order_2
                                    ]);
                                }
                            }
                            if(!empty($model->up_produksi) || $model->up_produksi != 0){
                                $upproduksi = $stock * ($model->up_produksi/100);
                                $stockItem->attributes = $val->attributes;
                                $stockItem->onhand = $stockItem->onhand + $upproduksi;
                                $stockItem->onsales = $stockItem->onsales - $upproduksi;
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
                                $stockTransaction->type_document = "CANCEL ORDER";
                                $stockTransaction->status_document = "IN (UP ".$model->up_produksi." %)";
                                $stockTransaction->qty_in = $upproduksi;
                                if(!$stockTransaction->save()){
                                    $success = false;
                                    $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION (UP PRODUKSI): ' : '';
                                    foreach($stockTransaction->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($spkOrder->errors) > 0) ? 'ERROR UPDATE SPK: ' : '';
                            foreach($spkOrder->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Dokumen ini sudah tidak bisa di cancel order. Proses produksi sudah berjalan.';
                    }
                }

                if($success){
                    $model->status = 0;
                    $model->post = 2;
                    if($model->save()){
                        foreach($model->items as $item){
                            $item->status = 0;
                            if(!$item->save()){
                                $success = false;
                                $message = (count($item->errors) > 0) ? 'ERROR CANCEL ITEM SALES ORDER: ' : '';
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
                                $message = (count($potong->errors) > 0) ? 'ERROR CANCEL POTONG SALES ORDER: ' : '';
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
                                $message = (count($proses->errors) > 0) ? 'ERROR CANCEL PROSES PRODUKSI SALES ORDER: ' : '';
                                foreach($proses->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CANCEL SALES ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }

                if($success){
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS CANCEL SALES ORDER.';
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

    public function actionOnChangeUp($qty, $up)
    {
        $request = \Yii::$app->request;
        $up_produksi = (new TempSalesOrderItem())->up_produksi($qty, $up);
        $total_qty_up = 0;
        if($up_produksi['total_up'] > 0){
            $total_qty_up = number_format($up_produksi['total_up']);
        }
        return json_encode(['total_qty_up'=>$total_qty_up]);
    }

    public function actionTypeOrder($type)
    {
        if($type == 2){
            $item = MasterMaterial::find()->alias('a')
                ->leftJoin('master_kode b', 'b.code = a.material_code')
                ->where(['value' => \Yii::$app->params['TYPE_PRODUK_JASA']])
                ->one();
            $data = ['item_name' => $item->name, 'item_code' => $item->code];
        }else{
            $data = ['item_name' => null, 'item_code' => null];
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
        return json_encode($model);
    }
    
    public function actionListItem($type)
    {
        $andWhere = '';
        if($type == 'item')
            $andWhere = 'value = "'.\Yii::$app->params['TYPE_KERTAS'].'" 
                and um_1 <> "'.\Yii::$app->params['TYPE_ROLL'].'"';
        if($type == 'bahan')
            $andWhere = 'value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"';

        $model = InventoryStockItem::find()
            ->alias('a')
            ->select(['item_code', 'onhand', 'b.code as supplier_code', 'b.name as supplier_name',
                'c.name as item_name', 'd.name as type_name'])
            ->leftJoin('master_person b', 'b.code = a.supplier_code')
            ->leftJoin('master_material c', 'c.code = a.item_code')
            ->leftJoin('master_kode d', 'd.code = c.type_code')
            ->leftJoin('master_satuan e', 'e.code = c.satuan_code')
            ->where(['a.status'=>1])
            ->andWhere($andWhere)
            ->orderBy(['item_code'=>SORT_ASC])
            ->asArray()
            ->limit(10)
            ->all();
        foreach($model as $index=>$val){
            $model[$index]['stock'] = InventoryStockItem::konversi($val['item_code'], $val['onhand']);
        }
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
                $andWhere = 'value = "'.\Yii::$app->params['TYPE_KERTAS'].'" 
                    and um_1 <> "'.\Yii::$app->params['TYPE_ROLL'].'"';
            if($_POST['type'] == 'bahan')
                $andWhere = 'value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"';

            $model = InventoryStockItem::find()
                ->alias('a')
                ->select(['concat(c.code, "-", c.name, " (", b.name, ")") as label', 'item_code', 'onhand',
                    'b.code as supplier_code', 'b.name as supplier_name', 'c.name as item_name', 'd.name as type_name'])
                ->leftJoin('master_person b', 'b.code = a.supplier_code')
                ->leftJoin('master_material c', 'c.code = a.item_code')
                ->leftJoin('master_kode d', 'd.code = c.type_code')
                ->leftJoin('master_satuan e', 'e.code = c.satuan_code')
                ->where(['a.status'=>1])
                ->andWhere('concat(c.code,"-", c.name, " (", b.name, ")") LIKE "%'.$_POST['search'].'%"')
                ->andWhere($andWhere)
                ->orderBy(['item_code'=>SORT_ASC])
                ->asArray()
                ->limit(10)
                ->all();
            foreach($model as $index=>$val){
                $model[$index]['stock'] = InventoryStockItem::konversi($val['item_code'], $val['onhand']);
            }
        }
        return  json_encode($model);
    }

    public function actionSearchItem()
    {
        $model = [];
        if(isset($_POST['code'])){
            $andWhere = '';
            if($_POST['type'] == 'item')
                $andWhere = 'value = "'.\Yii::$app->params['TYPE_KERTAS'].'" 
                    and um_1 <> "'.\Yii::$app->params['TYPE_ROLL'].'"';
            if($_POST['type'] == 'bahan')
                $andWhere = 'value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"';

            $model = InventoryStockItem::find()
                ->alias('a')
                ->select(['item_code', 'onhand', 'b.code as supplier_code', 'b.name as supplier_name', 
                    'c.name as item_name', 'd.name as type_name'])
                ->leftJoin('master_person b', 'b.code = a.supplier_code')
                ->leftJoin('master_material c', 'c.code = a.item_code')
                ->leftJoin('master_kode d', 'd.code = c.type_code')
                ->leftJoin('master_satuan e', 'e.code = c.satuan_code')
                ->where(['item_code'=>$_POST['code'], 'b.code'=>$_POST['supplier'], 'a.status'=>1])
                ->andWhere($andWhere)
                ->orderBy(['item_code'=>SORT_ASC])
                ->asArray()
                ->limit(10)
                ->all();
            foreach($model as $index=>$val){
                $model[$index]['stock'] = InventoryStockItem::konversi($val['item_code'], $val['onhand']);
            }
        }
        return json_encode(['data'=>$this->renderPartial('_list_item', [
            'model'=>$model, 'type'=>$_POST['type']])
        ]);
    }

    public function actionSelectItem()
    {
        $model = InventoryStockItem::find()
            ->alias('a')
            ->select(['item_code', 'b.code as supplier_code', 'c.name as item_name'])
            ->leftJoin('master_person b', 'b.code = a.supplier_code')
            ->leftJoin('master_material c', 'c.code = a.item_code')
            ->where(['item_code'=>$_POST['code'], 'supplier_code'=>$_POST['supplier'], 'a.status'=>1])
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
            ->where(['value'=>\Yii::$app->params['TYPE_KERTAS'], 'user_id'=> \Yii::$app->user->id])
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
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"')
            ->all();
        $model = $this->renderAjax('_temp_bahan', [
            'temps' => $temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionTempProses()
    {
        $temps = TempSalesOrderProses::findAll(['user_id' => \Yii::$app->user->id]);
        $tempItems = TempSalesOrderItem::findAll(['user_id' => \Yii::$app->user->id]);
        $code = '';
        foreach($tempItems as $val) $code = $val->code;
        $model = $this->renderAjax('_temp_proses', ['temps' => $temps, 'code' => $code]);
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
                        $tempPotong->attributes = (array)$dataItem;
                        $tempPotong->attributes = (array)$dataPotong;
                        $tempPotong->code = $code;
                        $tempPotong->urutan = $tempPotong->countTemp +1;
                        $tempPotong->user_id = \Yii::$app->user->id;
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
        $message = 'DELETE ITEM TEMP SUCCESSFULLY.';
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
            ->leftJoin('master_material b', 'b.code = a.item_code')
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
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $dataHeader = $request->post('SalesOrder');
                $code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
                $isRIM = ($dataHeader['type_qty'] == 1) ? true : false;
                // TEMP ITEM ATTRIBUTES
                $dataItem = $request->post('TempSalesOrderItem');
                $tempItem = new TempSalesOrderItem();
                $tempItem->attributes = (array)$dataItem;
                if(count($tempItem->itemBahan) > 0){
                    if(!empty($tempItem->bahan_qty)){
                        $tempItem->item_code = $tempItem->bahan_item_code;
                        $tempItem->supplier_code = $tempItem->bahan_supplier_code;
                        $tempItem->qty_order_1 = $tempItem->bahan_qty;
                        $tempItem->qty_up = null;
                        $tempItem->total_potong = 0;
                    }else{
                        $success = false;
                        $message = 'Qty bahan pembantu tidak boleh kosong.';
                        $tempItem->qty_up = null;
                        $tempItem->qty_order_1 = null;
                    }
                }

                $tempItem->attributes = $tempItem->item->attributes;
                if(isset($tempItem->itemPricelist)){
                    $tempItem->attributes = $tempItem->itemPricelist->attributes;
                    $tempItem->attributes = $tempItem->satuan->attributes;
                    $tempItem->attributes = $tempItem->item->attributes;
                    $tempItem->code = $code;
                    $tempItem->urutan = $tempItem->countTemp +1;
                    $tempItem->total_order = $tempItem->totalOrder;
                    $tempItem->user_id = \Yii::$app->user->id;
                    if($tempItem->item->typeCode->value == \Yii::$app->params['TYPE_KERTAS']){
                        $tempItem->supplier_code = $dataItem['supplier_code'];
                        
                        $totalQtyKonv = $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                            0=>$dataHeader['total_qty'], 1=>0]);
                        $totalQtyOrder = ($isRIM)
                            ? $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                                0=>$tempItem->qty_order_1, 1=>0])
                            : $tempItem->qty_order_2;
                        $totalQtyUp = str_replace(',', '', $tempItem->qty_up);
                        foreach($tempItem->itemsMaterial as $val){
                            $totalQtyUp += $val->qty_up;
                            if($isRIM){
                                $konv = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                    0=>$val->qty_order_1, 1=>0]);
                                $totalQtyOrder += $konv;
                            }else{
                                $totalQtyOrder += $val->qty_order_2;
                            }
                        }
                        
                        if($isRIM){
                            if(empty($tempItem->qty_order_1)){
                                $success = false;
                                $message = 'Qty order tidak boleh kosong (wajib di isi).';
                            }
                        }else{
                            if(empty($tempItem->qty_order_2)){
                                $success = false;
                                $message = 'Qty order tidak boleh kosong (wajib di isi).';
                            }
                        }
                        if($totalQtyOrder > $totalQtyKonv){
                            $success = false;
                            $message = 'Total qty order tidak boleh lebih dari '.$dataHeader['total_qty'];
                        }
                        $totalQtyUpHeader = str_replace(',', '', $dataHeader['total_qty_up']);
                        if($totalQtyUp > $totalQtyUpHeader){
                            $success = false;
                            $message = 'Total up produksi tidak boleh lebih dari '.$dataHeader['total_qty_up'];
                        }
                    }else{
                        $tempItem->supplier_code = $dataItem['bahan_supplier_code'];
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
                        $message = 'Item sudah disimpan.';
                    }
                }
                
                if($success){
                    $transaction->commit();
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
        $message = 'UPDATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $dataHeader = $request->post('SalesOrder');
                $dataItem = $request->post('TempSalesOrderItem');
                $tempItem = TempSalesOrderItem::findOne(['id'=>$dataItem['id']]);
                $isRIM = ($dataHeader['type_qty'] == 1) ? true : false;
                $code = $tempItem->code;
                $urutan = $tempItem->urutan;
                $supplierCode = $dataItem['supplier_code'];
                $qtyOrder = ($isRIM) ? 
                    $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                        0=>$tempItem->qty_order_1, 1=>0])
                    : $tempItem->qty_order_2;
                $qtyUp = $tempItem->qty_up;

                $tempItem->attributes = (array)$dataItem;
                $tempItem->attributes = $tempItem->item->attributes;
                if(isset($tempItem->itemPricelist)){
                    $tempItem->attributes = $tempItem->itemPricelist->attributes;
                    $tempItem->attributes = $tempItem->satuan->attributes;
                    $tempItem->attributes = $tempItem->item->attributes;
                    $tempItem->code = $code;
                    $tempItem->urutan = $urutan;
                    $tempItem->supplier_code = $supplierCode;
                    $tempItem->total_order = $tempItem->totalOrder;
                    if($tempItem->item->typeCode->value == \Yii::$app->params['TYPE_KERTAS']){
                        $totalQtyKonv = $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                            0=>$dataHeader['total_qty'], 1=>0]);
                        $totalQtyOrder = ($isRIM) ? 
                            $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                                0=>$tempItem->qty_order_1, 1=>0])
                            : $tempItem->qty_order_2;
                        $totalQtyUp = str_replace(',', '', $tempItem->qty_up);
                        foreach($tempItem->itemsMaterial as $val){
                            $totalQtyUp += $val->qty_up;
                            if($isRIM){
                                $konv = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                    0=>$val->qty_order_1, 1=>0]);
                                $totalQtyOrder += $konv;
                            }else{
                                $totalQtyOrder += $val->qty_order_2;
                            }
                        }
                        
                        if($isRIM){
                            if(empty($tempItem->qty_order_1)){
                                $success = false;
                                $message = 'Qty order tidak boleh kosong (wajib di isi).';
                            }
                        }else{
                            if(empty($tempItem->qty_order_2)){
                                $success = false;
                                $message = 'Qty order tidak boleh kosong (wajib di isi).';
                            }
                        }
                        $totalQtyOrder = $totalQtyOrder - $qtyOrder;
                        if($totalQtyOrder > $totalQtyKonv){
                            $success = false;
                            $message = 'Total qty order tidak boleh lebih dari '.$dataHeader['total_qty'];
                        }
                        $totalQtyUpHeader = str_replace(',', '', $dataHeader['total_qty_up']);
                        $totalQtyUp = $totalQtyUp - $qtyUp;
                        if($totalQtyUp > $totalQtyUpHeader){
                            $success = false;
                            $message = 'Total up produksi tidak boleh lebih dari '.$dataHeader['total_qty_up'];
                        }
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
                    if(count($tempItem->tempPotongs) <= $tempItem->total_potong){
                        if(!$tempItem->save()){
                            $success = false;
                            foreach($tempItem->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        $success = false;
                        $message = 'Detail potong tidak boleh lebih besar dari total potong.';
                    }
                }
                
                if($success){
                    $transaction->commit();
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
        $message = 'DELETE ITEM TEMP SUCCESSFULLY.';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try{
            $temp = TempSalesOrderItem::findOne(['id'=>$id]);
            if($temp->delete()){
                if(count($temp->temps) > 0){
                    foreach($temp->temps as $index=>$val){
                        $val->item_name = (isset($temp->item)) ? $temp->item->name : '';
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
                    TempSalesOrderProses::deleteAll('code=:code and item_code=:item_code and user_id=:user_id', [
                        ':code'=>$temp->code, ':item_code'=>$temp->item_code, ':user_id'=>\Yii::$app->user->id]);
                }
                TempSalesOrderPotong::deleteAll('code=:code and item_code=:item_code and supplier_code=:supplier_code and user_id=:user_id', [
                    ':code'=>$temp->code, ':item_code'=>$temp->item_code, ':supplier_code'=>$temp->supplier_code, ':user_id'=>\Yii::$app->user->id]);
            }else{
                $success = false;
                foreach($temp->errors as $error => $value){
                    $message = $value[0].', ';
                }
                $message = substr($message, 0, -2);
            }
            
            if($success){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch(\Exception $e){
            $success = false;
            $message = $e->getMessage();
            $transaction->rollBack();
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionListProses($code)
    {
        $data = [];
        $model = MasterProses::findAll(['status'=>1]);
        $tempItem = TempSalesOrderItem::findOne(['code'=>$code]);
        foreach($model as $val){
            $data[$val->code] = [
                'name' => $val->name,
                'proses_code' => $val->code,
                'type' => ($val->type == 1) ? 'Cetak' : 'Pond',
                'code' => $tempItem->code,
                'item_code' => $tempItem->item_code,
            ];
        }
        if(count($tempItem->tempProses) > 0){
            foreach($tempItem->tempProses as $val){
                $data[$val->proses_code] = [
                    'id' => $val->id,
                    'name' => (isset($val->prosesProduksi)) ? $val->prosesProduksi->name : '-',
                    'proses_code' => $val->proses_code,
                    'type' => ($val->type == 1) ? 'Cetak' : 'Pond',
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
            if(!empty($dataProses['proses_code'])){
                TempSalesOrderProses::deleteAll('code=:code and item_code=:item_code and user_id=:user_id', [
                    ':code'=>$dataProses['code'], ':item_code'=>$dataProses['item_code'], ':user_id'=>\Yii::$app->user->id]);
                foreach($dataProses['proses_code'] as $val){
                    $tempProses = new TempSalesOrderProses();
                    $prosesProduksi = $tempProses->prosesProduksi($val);
                    $tempProses->attributes = $prosesProduksi->attributes;
                    $tempProses->attributes = (array)$dataProses;
                    $tempProses->proses_code = $val;
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
                                $stockItem->attributes = $val->attributes;
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
                    $stock = 0;
                    foreach($model->itemsMaterial as $index=>$val){
                        $stockItem = $val->inventoryStock;
                        if(isset($stockItem)){
                            if($stockItem->onhand > $val->qty_up){
                                $stockItem->attributes = $val->attributes;
                                $stockItem->onhand = $stockItem->onhand - $val->qty_up;
                                $stockItem->onsales = $stockItem->onsales + $val->qty_up;
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
                                $stockTransaction->status_document = "OUT (UP ".$model->up_produksi." %)";
                                $stockTransaction->qty_out = $val->qty_up;
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
                    
                    // PROSES SIMPAN SPK ORDER
                    $spkOrder = new SpkOrder();
                    $spkOrder->attributes = $model->attributes;
                    $spkOrder->no_so = $model->code;
                    $spkOrder->no_spk = $spkOrder->generateCode();
                    $spkOrder->tgl_spk = date('Y-m-d');
                    if($spkOrder->save()){
                        $dataPotong = [];
                        foreach($model->potongs as $val){
                            $uk_potong = $val->lebar.'x'.$val->panjang;
                            $dataPotong[$val->item_code][$uk_potong] = [
                                'potong_id' => $val->urutan,
                                'uk_potong' => $uk_potong,
                            ];
                        }

                        $dataItem = [];
                        foreach($model->itemsMaterial as $val){
                            $stock = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                0=>$val->qty_order_1,
                                1=>$val->qty_order_2,
                            ]);
                            $qty = $stock + $val->qty_up;
                            $dataItem[$val->item_code][] = ['qty' => $qty];
                        }
                        // JUMLAHKAN TOTAL MATERIAL DENGAN ITEM YANG SAMA
                        $totalQty = 0;
                        foreach($dataItem as $item=>$index){
                            foreach($index as $val){
                                $totalQty += $val['qty'];
                            }
                            $dataItem[$item] = $totalQty;
                        }
                        
                        $uid = 1;
                        foreach($model->proses as $val){
                            foreach($dataPotong[$val->item_code] as $_val){
                                $spkProses = new SpkOrderProses();
                                $spkProses->attributes = $val->attributes;
                                $spkProses->attributes = (array)$_val;
                                $spkProses->no_spk = $spkOrder->no_spk;
                                $spkProses->proses_id = $uid++;
                                $spkProses->proses_type = $val->type;
                                $spkProses->gram = (isset($val->item)) ? $val->item->gram : NULL;
                                $spkProses->qty_proses = $dataItem[$val->item_code];
                                if(!$spkProses->save()){
                                    $success = false;
                                    $message = (count($spkProses->errors) > 0) ? 'ERROR CREATE SPK PROSES: ' : '';
                                    foreach($spkProses->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($spkOrder->errors) > 0) ? 'ERROR CREATE SPK: ' : '';
                        foreach($spkOrder->errors as $error => $value){
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