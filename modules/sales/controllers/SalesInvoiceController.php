<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\sales\models\RequestOrder;
use app\modules\sales\models\SalesInvoice;
use app\modules\sales\models\SalesInvoiceDetail;
use app\modules\sales\models\SalesInvoiceItem;
use app\modules\sales\models\SalesInvoiceSearch;
use app\modules\sales\models\SalesOrder;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SalesInvoiceController implements the CRUD actions for SalesInvoice model.
 */
class SalesInvoiceController extends Controller
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
                            'actions' => ['create'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'detail-sales-order', 'detail-request-order', 'popup-remark-harga'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[R]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[U]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[D]')),
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
     * Lists all SalesInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalesInvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SalesInvoice model.
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
     * Creates a new SalesInvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $listSalesOrder = SalesOrder::find()
            ->alias('a')
            ->select(['concat(code, " - ", a.name)'])
            ->leftJoin('spk_order b', 'b.no_so = a.code')
            ->where(['post' => 1, 'status_produksi' => 4])
            ->indexBy('code')
            ->column();
        $model = new SalesInvoice();
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->no_invoice = $model->generateCode();
                    $model->tgl_invoice = date('Y-m-d');

                    $total_order_material = 0;
                    $total_order_bahan = 0;
                    $total_biaya_produksi = 0;
                    $total_ppn = 0;
                    $grand_total = 0;
                    /** +++++++++ CREATE FROM SALES ORDER +++++++++ */
                    $salesOrder = SalesOrder::findOne(['code' => $model->no_so]);
                    if(isset($salesOrder)){
                        $total_order_material += $salesOrder->total_order_material;
                        $total_order_bahan += $salesOrder->total_order_bahan;
                        $total_biaya_produksi += $salesOrder->total_biaya_produksi;
                        $total_ppn += $salesOrder->total_ppn;
                        $grand_total += $salesOrder->grand_total;

                        $detail = new SalesInvoiceDetail();
                        $detail->attributes = $salesOrder->attributes;
                        $detail->no_invoice = $model->no_invoice;
                        $detail->no_sales = $model->no_so;
                        $detail->urutan = 1;
                        $detail->type_invoice = 1;
                        $detail->keterangan = null;
                        if($detail->save()){
                            foreach($salesOrder->items as $val){
                                $item = new SalesInvoiceItem();
                                $item->attributes = $detail->attributes;
                                $item->attributes = $val->attributes;
                                $item->urutan = $item->count +1;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR CREATE SALES INVOICE ITEM (SO): ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                            foreach($salesOrder->proses as $val){
                                $item = new SalesInvoiceItem();
                                $item->attributes = $detail->attributes;
                                $item->attributes = $val->attributes;
                                $item->harga_jual_1 = $val->harga;
                                $item->total_order = $val->total_biaya;
                                $item->urutan = $item->count +1;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR CREATE SALES INVOICE ITEM (SO): ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }

                            $salesOrder->post = 3;
                            if(!$salesOrder->save()){
                                $success = false;
                                $message = (count($salesOrder->errors) > 0) ? 'ERROR UPDATE SALES ORDER: ' : '';
                                foreach($salesOrder->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = (count($detail->errors) > 0) ? 'ERROR CREATE SALES INVOICE DETAIL (SO): ' : '';
                            foreach($detail->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                    /** +++++++++ END CREATE FROM SALES ORDER +++++++++ */
                    
                    /** +++++++++ CREATE FROM REQUEST ORDER +++++++++ */
                    $requestOrder = RequestOrder::findOne(['no_so' => $model->no_so]);
                    if(isset($requestOrder)){
                        $total_order_material += $requestOrder->total_order_material;
                        $total_order_bahan += $requestOrder->total_order_bahan;
                        $grand_total += $requestOrder->grand_total;

                        $detail = new SalesInvoiceDetail();
                        $detail->attributes = $requestOrder->attributes;
                        $detail->no_invoice = $model->no_invoice;
                        $detail->urutan = 2;
                        $detail->type_invoice = 2;
                        $detail->keterangan = null;
                        if($detail->save()){
                            foreach($requestOrder->items as $val){
                                $item = new SalesInvoiceItem();
                                $item->attributes = $detail->attributes;
                                $item->attributes = $val->attributes;
                                $item->urutan = $item->count +1;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR CREATE SALES INVOICE ITEM (RO): ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($detail->errors) > 0) ? 'ERROR CREATE SALES INVOICE DETAIL (RO): ' : '';
                            foreach($detail->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                    /** +++++++++ END CREATE FROM REQUEST ORDER +++++++++ */
                    
                    $model->total_order_material = $total_order_material;
                    $model->total_order_bahan = $total_order_bahan;
                    $model->total_biaya_produksi = $total_biaya_produksi;
                    $model->total_ppn = $total_ppn;
                    $model->grand_total = $grand_total;
                    if(!$model->save()){
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE SALES INVOICE: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $transaction->commit();
                        $message = '['.$model->no_invoice.'] SUCCESS CREATE SALES INVOICE.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
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
        }

        return $this->render('create', [
            'model' => $model,
            'listSalesOrder' => $listSalesOrder,
        ]);
    }

    /**
     * Updates an existing SalesInvoice model.
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
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{

                    return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
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

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SalesInvoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_invoice No Invoice
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_invoice)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_invoice);
        if(isset($model)){
            $connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
            try{
                $model->status = 0;
                if($model->save()){
                    foreach($model->details as $val){
                        $val->status = 0;
                        if(!$val->save()){
                            $success = false;
                            $message = (count($val->errors) > 0) ? 'ERROR DELETE SALES INVOICE DETAIL: ' : '';
                            foreach($val->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                    foreach($model->items as $val){
                        $val->status = 0;
                        if(!$val->save()){
                            $success = false;
                            $message = (count($val->errors) > 0) ? 'ERROR DELETE SALES INVOICE ITEM: ' : '';
                            foreach($val->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE SALES INVOICE: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = '['.$model->no_invoice.'] SUCCESS DELETE SALES INVOICE.';
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
     * Finds the SalesInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_invoice No Invoice
     * @return SalesInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_invoice)
    {
        if (($model = SalesInvoice::findOne($no_invoice)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetailSalesOrder()
    {
        $data = [];
        $post = $_POST['SalesInvoice'];
        if(isset($post['no_so'])){
            $model = SalesOrder::find()->where(['code'=>$post['no_so']])->all();
            $data = $this->renderAjax('detail_sales_order', [
                'model' => $model
            ]);
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['data'=>$data]);
    }

    public function actionDetailRequestOrder()
    {
        $data = [];
        $post = $_POST['SalesInvoice'];
        if(isset($post['no_so'])){
            $model = RequestOrder::find()->where(['no_so'=>$post['no_so']])->all();
            $data = $this->renderAjax('detail_request_order', [
                'model' => $model
            ]);
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['data'=>$data]);
    }

    public function actionPopupRemarkHarga($no_invoice, $type_invoice, $urutan)
    {
        $item = SalesInvoiceItem::findOne(['no_invoice'=>$no_invoice, 'type_invoice'=>$type_invoice, 'urutan'=>$urutan]);
        return json_encode(['data'=>$this->renderPartial('popup_remark_harga', [
            'item'=>$item])
        ]);
    }
}