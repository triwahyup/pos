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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'terima', 'update-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('purchase-order')),
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
                            if($val->qty_terima==0){
                                $success = false;
                                $message = 'QTY Terima item '.$val->item_code.'-'.$val->name.' masih 0.';
                            }
                            $total_ppn += $val->ppn;
                        }
                        if($success){
                            $model->total_ppn = $total_ppn;
                            if($model->save()){
                                $transaction->commit();
                                $message = 'UPDATE INVOICE ORDER: '.$model->no_invoice;
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
                    foreach($model->details as $val){
                        if($val->qty_terima==0){
                            $success = false;
                            $message = 'QTY Terima item '.$val->item_code.'-'.$val->name.' masih 0.';
                        }
                    }

                    if($success){
                        $model->status_terima=1;
                        $model->post=1;
                        $purchaseOrder = PurchaseOrder::findOne(['no_po'=>$model->no_po]);
                        $purchaseOrder->status_terima=1;
                        if($model->save() && $purchaseOrder->save()){
                            foreach($model->details as $val){
                                $stockItem = InventoryStockItem::findOne(['item_code'=>$val->item_code, 'status'=>1]);
                                if(isset($stockItem)){
                                    $stockItem->qty_in = $stockItem->qty_in+$val->qty_terima;
                                    $stockItem->onhand = $stockItem->onhand+$val->qty_terima;
                                    if(!$stockItem->save()){
                                        $success = false;
                                        $message = (count($stockItem->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                        foreach($stockItem->errors as $error => $value){
                                            $message .= strtoupper($value[0].', ');
                                        }
                                        $message = substr($message, 0, -2);
                                    }

                                    $stockTransaction = new InventoryStockTransaction();
                                    $stockTransaction->item_code = $val->item_code;
                                    $stockTransaction->supplier_code = $model->supplier_code;
                                    $stockTransaction->no_document = $model->no_invoice;
                                    $stockTransaction->tgl_document = $model->tgl_invoice;
                                    $stockTransaction->type_document = "INVOICE ORDER";
                                    $stockTransaction->status_document = "IN";
                                    $stockTransaction->qty_in = $val->qty_terima;
                                    $stockTransaction->onhand = (isset($stockTransaction->onHand)) ? $stockTransaction->onHand->qty_in+$val->qty_terima : $val->qty_terima;
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
                                    $message = 'Item {'.$val->item_code.'-'.$val->name.'} tidak ditemukan di Inventory Stock Item';
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
                        $message = 'TERIMA INVOICE ORDER: '.$model->no_invoice.' SUCCESS';
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
        $temp = PurchaseOrderInvoiceDetail::findOne(['no_invoice'=>$no_invoice, 'urutan'=>$urutan]);
        $data['urutan'] = $temp->urutan;
        $data['harga_beli'] = $temp->harga_beli;
        $data['ppn'] = $temp->ppn;
        $data['qty_terima'] = (!empty($temp->qty_terima)) ? $temp->qty_terima : $temp->qty_order;
        return json_encode($data);
    }

    public function actionUpdateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        $invoiceOrder = $request->post('PurchaseOrderInvoice');
        if($request->isPost){
            $temp = PurchaseOrderInvoiceDetail::findOne(['no_invoice'=>$invoiceOrder['no_invoice'], 'urutan'=>$invoiceOrder['urutan']]);
            $temp->attributes = (array)$invoiceOrder;
            if($temp->qty_terima <= $temp->qty_order){
                $temp->harga_beli = str_replace(',','', $temp->harga_beli);
                $temp->qty_terima = str_replace(',','', $temp->qty_terima);
                if(!empty($temp->ppn)){
                    $ppn = $temp->harga_beli * $temp->qty_terima / ($temp->ppn*100);
                    $temp->total_invoice = ($temp->harga_beli * $temp->qty_terima) + $ppn;
                }else{
                    $temp->total_invoice = $temp->harga_beli * $temp->qty_terima;
                }
                if($temp->save()){
                    $message = 'UPDATE TERIMA SUCCESSFULLY';
                }else{
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