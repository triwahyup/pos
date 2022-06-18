<?php

namespace app\modules\purchasing\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\purchasing\models\PurchaseOrder;
use app\modules\purchasing\models\PurchaseOrderInvoice;
use app\modules\purchasing\models\PurchaseOrderInvoiceDetail;
use app\modules\purchasing\models\PurchaseOrderInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * InvoiceOrderController implements the CRUD actions for PurchaseOrderInvoice model.
 */
class InvoiceOrderController extends Controller
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
                            'actions' => ['index', 'view', 'temp', 'get-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('invoice-material[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'terima', 'close', 'update-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('invoice-material[U]')),
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
     * Lists all PurchaseOrderInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseOrderInvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseOrderInvoice model.
     * @param string $no_invoice No Invoice
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_invoice)
    {
        return $this->render('view', [
            'model' => $this->findModel($no_invoice),
        ]);
    }

    /**
     * Updates an existing PurchaseOrderInvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_invoice No Invoice
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_invoice)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($no_invoice);
        if ($this->request->isPost ) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    if($model->no_bukti!="" && $model->tgl_invoice!=""){
                        $total_ppn=0;
                        foreach($model->details as $val){
                            if($val->qty_terima_1==0){
                                $success = false;
                                $message = 'QTY Terima item '.$val->item_code.'-'.$val->name.' masih 0.';
                            }
                            $total_ppn += $val->ppn;
                        }
                        if($success){
                            $model->total_ppn = $total_ppn;
                            if($model->save()){
                                $transaction->commit();
                                $message = '['.$model->no_invoice.'] SUCCESS UPDATE INVOICE ORDER.';
                                $logs =	[
                                    'type' => Logs::TYPE_USER,
                                    'description' => $message,
                                ];
                                Logs::addLog($logs);
    
                                \Yii::$app->session->setFlash('success', $message);
                                return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
                            }else{
                                $success = false;
                                $message = (count($model->errors) > 0) ? 'ERROR UPDATE INVOICE ORDER: ' : '';
                                foreach($model->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $transaction->rollback();
                        }
                    }else{
                        $success = false;
                        $message = 'No. Bukti dan Tgl. Invoice tidak boleh kosong.';
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
            if($model->status_terima == 1 || $model->status_terima == 3){
                \Yii::$app->session->setFlash('error', 'Dokumen ini sudah diterima.');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the PurchaseOrderInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_invoice No Invoice
     * @return PurchaseOrderInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_invoice)
    {
        if (($model = PurchaseOrderInvoice::findOne($no_invoice)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionTerima($no_invoice)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($no_invoice);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if(!empty($model->no_bukti) && !empty($model->tgl_invoice)){
                    $selisih = false;
                    foreach($model->details as $val){
                        // TERIMA SEBAGIAN
                        $qtySelisih = $val->getQtySelisih($val->qty_order_1, $val->qty_terima_1);
                        if(!$qtySelisih['isEmptyQty']){
                            if($qtySelisih['selisih'] == -1){
                                $selisih = true;
                            }
                        }else{
                            $success = false;
                            $message = 'QTY Terima item '.$val->item_code.'-'.$val->name.' masih 0.';
                        }
                    }

                    if($success){
                        $model->status_terima = ($selisih) ? 2 : 1;
                        $model->post=1;
                        $purchaseOrder = PurchaseOrder::findOne(['no_po'=>$model->no_po]);
                        $purchaseOrder->status_terima = ($selisih) ? 2 : 1;
                        if($model->save() && $purchaseOrder->save()){
                            foreach($model->details as $val){
                                // STOCK IN
                                $stockItem = InventoryStockItem::findOne(['item_code'=>$val->item_code, 'supplier_code'=>$val->supplier_code, 'status'=>1]);
                                if(empty($stockItem)){
                                    $stockItem = new InventoryStockItem();
                                }
                                $konversi = $stockItem->satuanTerkecil($val->item_code, [
                                    0 => $val->qty_terima_1,
                                    1 => $val->qty_terima_2
                                ]);
                                $stockItem->attributes = $val->attributes;
                                $stockItem->onhand = $stockItem->onhand+$konversi;
                                if(!$stockItem->save()){
                                    $success = false;
                                    $message = (count($stockItem->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                    foreach($stockItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $stockTransaction = new InventoryStockTransaction();
                                $stockTransaction->attributes = $val->attributes;
                                $stockTransaction->no_document = $model->no_invoice;
                                $stockTransaction->tgl_document = $model->tgl_invoice;
                                $stockTransaction->type_document = "INVOICE ORDER";
                                $stockTransaction->status_document = "IN";
                                $stockTransaction->qty_in = $konversi;
                                $stockTransaction->onhand = (isset($stockTransaction->onHand)) ? $stockTransaction->onHand->onhand+$konversi : $konversi;
                                if(!$stockTransaction->save()){
                                    $success = false;
                                    $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                    foreach($stockTransaction->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR TERIMA INVOICE ORDER: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
                    }

                    if($success){
                        $message = '['.$model->no_invoice.'] SUCCESS TERIMA INVOICE ORDER.';
                        $transaction->commit();
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
                    }else{
                        $transaction->rollback();
                    }
                }else{
                    $success = false;
                    $message = 'No. Bukti dan Tgl. Invoice tidak boleh kosong.';
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
            $message = 'Data Invoice Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
    }

    public function actionClose($no_invoice)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($no_invoice);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $selisih = false;
                foreach($model->details as $val){
                    $qtySelisih = $val->getQtySelisih($val->qty_order_1, $val->qty_terima_1);
                    if($qtySelisih['selisih'] == -1){
                        $selisih = true;
                    }
                }

                if($success){
                    $model->status_terima = ($selisih) ? 3 : 1;
                    $purchaseOrder = PurchaseOrder::findOne(['no_po'=>$model->no_po]);
                    $purchaseOrder->status_terima = ($selisih) ? 3 : 1;
                    if($model->save() && $purchaseOrder->save()){
                        foreach($model->details as $val){
                            // STOCK IN SUSULAN
                            $stockItem = InventoryStockItem::findOne(['item_code'=>$val->item_code, 'supplier_code'=>$val->supplier_code, 'status'=>1]);
                            $konversi = $stockItem->satuanTerkecil($val->item_code, [
                                0 => $val->qty_susulan, 1 => 0]);
                            if($konversi > 0){
                                $stockItem->onhand = $stockItem->onhand+$konversi;
                                if(!$stockItem->save()){
                                    $success = false;
                                    $message = (count($stockItem->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                    foreach($stockItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $stockTransaction = new InventoryStockTransaction();
                                $stockTransaction->attributes = $val->attributes;
                                $stockTransaction->no_document = $model->no_invoice;
                                $stockTransaction->tgl_document = $model->tgl_invoice;
                                $stockTransaction->type_document = "INVOICE ORDER (S)";
                                $stockTransaction->status_document = "IN";
                                $stockTransaction->qty_in = $konversi;
                                $stockTransaction->onhand = (isset($stockTransaction->onHand)) ? $stockTransaction->onHand->onhand+$konversi : $konversi;
                                if(!$stockTransaction->save()){
                                    $success = false;
                                    $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                    foreach($stockTransaction->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR TERIMA INVOICE ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }

                if($success){
                    $message = '['.$model->no_invoice.'] SUCCESS TERIMA INVOICE ORDER.';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
                }else{
                    $transaction->rollback();
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
            $message = 'Data Invoice Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
    }

    public function actionTemp()
    {
        $request = \Yii::$app->request;
        $temps = PurchaseOrderInvoiceDetail::findAll(['no_invoice'=>$request->post('PurchaseOrderInvoice')['no_invoice']]);
        $total_invoice=0;
        foreach($temps as $temp){
            $total_invoice += $temp->total_invoice;
        }
        $model =  $this->renderAjax('_temp', [
            'temps'=>$temps,
        ]);
        return json_encode(['total_invoice'=>number_format($total_invoice), 'model'=>$model]);
    }

    public function actionGetTemp($no_invoice, $urutan)
    {
        $temp = PurchaseOrderInvoiceDetail::find()
            ->where(['no_invoice'=>$no_invoice, 'urutan'=>$urutan])
            ->asArray()
            ->one();
        
        $success = true;
        $message = '';
        $model = PurchaseOrderInvoice::findOne(['no_invoice'=>$no_invoice]); 
        if($model->post == 1){
            if(($temp['qty_order_1'] - $temp['qty_terima_1']) == 0){
                $success = false;
                $message = 'Item ini sudah balance. Dokumen sudah di post, tidak bisa edit data ini.';
            }else{
                $temp['qty_terima_1'] = ($temp['qty_selisih'] > 0) ? $temp['qty_selisih'] : $temp['qty_terima_1'];
            }
        }else{
            $temp['qty_terima_1'] = ($temp['qty_terima_1'] > 0) ? $temp['qty_terima_1'] : $temp['qty_order_1'];
        }
        return json_encode(['temp'=>$temp, 'success'=>$success, 'message'=>$message]);
    }

    public function actionUpdateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'UPDATE TERIMA SUCCESSFULLY';
        $invoiceOrder = $request->post('PurchaseOrderInvoice');
        $model = PurchaseOrderInvoice::findOne(['no_invoice'=>$invoiceOrder]);
        if($request->isPost){
            $temp = PurchaseOrderInvoiceDetail::findOne(['no_invoice'=>$invoiceOrder['no_invoice'], 'urutan'=>$invoiceOrder['urutan']]);
            $qtyTerima = $temp->qty_terima_1;
            $temp->attributes = (array)$invoiceOrder;
            if($model->post == 1){
                $qtyOrder = ($temp->qty_selisih > 0) ? $temp->qty_selisih : $temp->qty_order_1;
            }else{
                $qtyOrder = $temp->qty_order_1;
            }
            
            if($temp->qty_terima_1 <= $qtyOrder){
                if($temp->qty_selisih > 0){
                    if($model->post == 1){
                        $temp->qty_terima_1 = $qtyTerima+$temp->qty_terima_1;
                    }else{
                        $temp->qty_terima_1 = $temp->qty_terima_1;
                    }
                }else{
                    $temp->qty_terima_1 = $temp->qty_terima_1;
                }

                $qtySelisih = $temp->getQtySelisih($temp->qty_order_1, $temp->qty_terima_1);
                $temp->qty_susulan = ($temp->qty_selisih > 0) ? $qtyOrder : 0;
                $temp->qty_selisih = $qtySelisih['qty'];
                $temp->total_invoice = $temp->totalInvoice;
                if(!$temp->save()){
                    $success = false;
                    foreach($temp->errors as $error => $value){
                        $message = $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
            }else{
                $success = false;
                $message = 'QTY Terima tidak boleh lebih besar dari QTY Order';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }
}