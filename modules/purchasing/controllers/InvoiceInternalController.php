<?php

namespace app\modules\purchasing\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockBarang;
use app\modules\inventory\models\InventoryStockBast;
use app\modules\purchasing\models\PurchaseInternal;
use app\modules\purchasing\models\PurchaseInternalInvoice;
use app\modules\purchasing\models\PurchaseInternalInvoiceDetail;
use app\modules\purchasing\models\PurchaseInternalInvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * InvoiceInternalController implements the CRUD actions for PurchaseInternalInvoice model.
 */
class InvoiceInternalController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('invoice-internal')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'terima', 'close', 'update-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('invoice-internal')),
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
     * Lists all PurchaseInternalInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseInternalInvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseInternalInvoice model.
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
     * Updates an existing PurchaseInternalInvoice model.
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
                            if($val->qty_terima==0){
                                $success = false;
                                $message = 'QTY Terima Barang '.$val->barang_code.'-'.$val->name.' masih 0.';
                            }
                            $total_ppn += $val->ppn;
                        }
                        if($success){
                            $model->total_ppn = $total_ppn;
                            if($model->save()){
                                $transaction->commit();
                                $message = '['.$model->no_invoice.'] SUCCESS UPDATE INVOICE INTERNAL.';
                                $logs =	[
                                    'type' => Logs::TYPE_USER,
                                    'description' => $message,
                                ];
                                Logs::addLog($logs);
    
                                \Yii::$app->session->setFlash('success', $message);
                                return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
                            }else{
                                $success = false;
                                $message = (count($model->errors) > 0) ? 'ERROR UPDATE INVOICE INTERNAL: ' : '';
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
     * Finds the PurchaseInternalInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_invoice No Invoice
     * @return PurchaseInternalInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_invoice)
    {
        if (($model = PurchaseInternalInvoice::findOne($no_invoice)) !== null) {
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
                        $qtySelisih = $val->getQtySelisih($val->qty_order, $val->qty_terima);
                        if(!$qtySelisih['isEmptyQty']){
                            if($qtySelisih['selisih'] == -1){
                                $selisih = true;
                            }
                        }else{
                            $success = false;
                            $message = 'QTY Terima Barang '.$val->barang_code.'-'.$val->name.' masih 0.';
                        }
                    }
                    
                    if($success){
                        $model->status_terima = ($selisih) ? 2 : 1;
                        $model->post=1;
                        $purchaseInternal = PurchaseInternal::findOne(['no_po'=>$model->no_po]);
                        $purchaseInternal->status_terima = ($selisih) ? 2 : 1;
                        if($model->save() && $purchaseInternal->save()){
                            foreach($model->details as $val){
                                // STOCK IN
                                $stockBarang = InventoryStockBarang::findOne(['barang_code'=>$val->barang_code, 'supplier_code'=>$val->supplier_code, 'status'=>1]);
                                if(empty($stockBarang)){
                                    $stockBarang = new InventoryStockBarang();
                                }
                                $stockBarang->attributes = $val->attributes;
                                $stockBarang->stock = $stockBarang->stock+$val->qty_terima;
                                if(!$stockBarang->save()){
                                    $success = false;
                                    $message = (count($stockBarang->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                    foreach($stockBarang->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $stockBast = new InventoryStockBast();
                                $stockBast->attributes = $val->attributes;
                                $stockBast->no_document = $model->no_invoice;
                                $stockBast->tgl_document = $model->tgl_invoice;
                                $stockBast->type_document = "INVOICE INTERNAL";
                                $stockBast->status_document = "IN";
                                $stockBast->qty_in = $val->qty_terima;
                                $stockBast->stock = (isset($stockBast->onHand)) ? $stockBast->onHand->stock+$val->qty_terima : $val->qty_terima;
                                if(!$stockBast->save()){
                                    $success = false;
                                    $message = (count($stockBast->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                    foreach($stockBast->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR TERIMA INVOICE INTERNAL: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
                    }

                    if($success){
                        $message = '['.$model->no_invoice.'] SUCCESS TERIMA INVOICE INTERNAL.';
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
                    $qtySelisih = $val->getQtySelisih($val->qty_order, $val->qty_terima);
                    if($qtySelisih['selisih'] == -1){
                        $selisih = true;
                    }
                }

                if($success){
                    $model->status_terima = ($selisih) ? 3 : 1;
                    $purchaseInternal = PurchaseInternal::findOne(['no_po'=>$model->no_po]);
                    $purchaseInternal->status_terima = ($selisih) ? 3 : 1;
                    if($model->save() && $purchaseInternal->save()){
                        foreach($model->details as $val){
                            // STOCK IN SUSULAN
                            $stockBarang = InventoryStockBarang::findOne(['barang_code'=>$val->barang_code, 'supplier_code'=>$val->supplier_code, 'status'=>1]);
                            if($val->qty_susulan > 0){
                                $stockBarang->stock = $stockBarang->stock+$val->qty_susulan;
                                if(!$stockBarang->save()){
                                    $success = false;
                                    $message = (count($stockBarang->errors) > 0) ? 'ERROR UPDATE STOCK BARANG: ' : '';
                                    foreach($stockBarang->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $stockBast = new InventoryStockBast();
                                $stockBast->attributes = $val->attributes;
                                $stockBast->no_document = $model->no_invoice;
                                $stockBast->tgl_document = $model->tgl_invoice;
                                $stockBast->type_document = "INVOICE INTERNAL (S)";
                                $stockBast->status_document = "IN";
                                $stockBast->qty_in = $val->qty_susulan;
                                $stockBast->stock = (isset($stockBast->onHand)) ? $stockBast->onHand->stock+$val->qty_susulan : $val->qty_susulan;
                                if(!$stockBast->save()){
                                    $success = false;
                                    $message = (count($stockBast->errors) > 0) ? 'ERROR UPDATE STOCK BAST: ' : '';
                                    foreach($stockBast->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR TERIMA INVOICE INTERNAL: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }

                if($success){
                    $message = '['.$model->no_invoice.'] SUCCESS TERIMA INVOICE INTERNAL.';
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
        $temps = PurchaseInternalInvoiceDetail::findAll(['no_invoice'=>$request->post('PurchaseInternalInvoice')['no_invoice']]);
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
        $temp = PurchaseInternalInvoiceDetail::find()
            ->where(['no_invoice'=>$no_invoice, 'urutan'=>$urutan])
            ->asArray()
            ->one();
        
        $success = true;
        $message = '';
        $model = PurchaseInternalInvoice::findOne(['no_invoice'=>$no_invoice]); 
        if($model->post == 1){
            if(($temp['qty_order'] - $temp['qty_terima']) == 0){
                $success = false;
                $message = 'Item ini sudah balance. Dokumen sudah di post, tidak bisa edit data ini.';
            }else{
                $temp['qty_terima'] = ($temp['qty_selisih'] > 0) ? $temp['qty_selisih'] : $temp['qty_terima'];
            }
        }else{
            $temp['qty_terima'] = ($temp['qty_terima'] > 0) ? $temp['qty_terima'] : $temp['qty_order'];
        }
        return json_encode(['temp'=>$temp, 'success'=>$success, 'message'=>$message]);
    }

    public function actionUpdateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'UPDATE TERIMA SUCCESSFULLY';
        $invoiceInternal = $request->post('PurchaseInternalInvoice');
        $model = PurchaseInternalInvoice::findOne(['no_invoice'=>$invoiceInternal]);
        if($request->isPost){
            $temp = PurchaseInternalInvoiceDetail::findOne(['no_invoice'=>$invoiceInternal['no_invoice'], 'urutan'=>$invoiceInternal['urutan']]);
            $qtyTerima = $temp->qty_terima;
            $temp->attributes = (array)$invoiceInternal;
            if($model->post == 1){
                $qtyOrder = ($temp->qty_selisih > 0) ? $temp->qty_selisih : $temp->qty_order;
            }else{
                $qtyOrder = $temp->qty_order;
            }
            
            if($temp->qty_terima <= $qtyOrder){
                if($temp->qty_selisih > 0){
                    if($model->post == 1){
                        $temp->qty_terima = $qtyTerima+$temp->qty_terima;
                    }else{
                        $temp->qty_terima = $temp->qty_terima;
                    }
                }else{
                    $temp->qty_terima = $temp->qty_terima;
                }

                $qtySelisih = $temp->getQtySelisih($temp->qty_order, $temp->qty_terima);
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
