<?php

namespace app\modules\sales\controllers;

use app\models\DataList;
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
                            'actions' => ['index', 'view', 'detail-sales-order', 'detail-request-order', 'popup-remark-harga', 'popup-biaya-lain'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[R]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['update', 'update-harga', 'update-biaya-lain'],
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
                        $detail->new_total_order_material = $salesOrder->total_order_material;
                        $detail->new_total_order_bahan = $salesOrder->total_order_bahan;
                        $detail->new_total_biaya_produksi = $salesOrder->total_biaya_produksi;
                        $detail->new_total_ppn = $salesOrder->total_ppn;
                        $detail->new_grand_total = $salesOrder->grand_total;
                        $detail->keterangan = null;
                        if($detail->save()){
                            foreach($salesOrder->items as $val){
                                $item = new SalesInvoiceItem();
                                $item->attributes = $detail->attributes;
                                $item->attributes = $val->attributes;
                                $item->urutan = $item->count +1;
                                $item->new_harga_jual_1 = $val->harga_jual_1;
                                $item->new_harga_jual_2 = $val->harga_jual_2;
                                $item->new_total_order = $val->total_order;
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
                                $item->urutan = $item->count +1;
                                $item->harga_jual_1 = $item->new_harga_jual_1 = $val->harga;
                                $item->total_order = $item->new_total_order = $val->total_biaya;
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
                        $detail->new_total_order_material = $requestOrder->total_order_material;
                        $detail->new_total_order_bahan = $requestOrder->total_order_bahan;
                        $detail->new_grand_total = $requestOrder->grand_total;
                        $detail->keterangan = null;
                        if($detail->save()){
                            foreach($requestOrder->items as $val){
                                $item = new SalesInvoiceItem();
                                $item->attributes = $detail->attributes;
                                $item->attributes = $val->attributes;
                                $item->urutan = $item->count +1;
                                $item->new_harga_jual_1 = $val->harga_jual_1;
                                $item->new_harga_jual_2 = $val->harga_jual_2;
                                $item->new_total_order = $val->total_order;
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
                    
                    $model->ppn = $salesOrder->ppn;
                    $model->total_order_material = $model->new_total_order_material = $total_order_material;
                    $model->total_order_bahan = $model->new_total_order_bahan = $total_order_bahan;
                    $model->total_biaya_produksi = $model->new_total_biaya_produksi = $total_biaya_produksi;
                    $model->total_ppn = $model->new_total_ppn = $total_ppn;
                    $model->grand_total = $model->new_grand_total = $grand_total;
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
        $model = $this->findModel($no_invoice);
        if($this->request->isPost){
            return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
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

    public function actionPopupBiayaLain($no_invoice, $type_invoice, $urutan)
    {
        $typeOngkos = DataList::listTypeOngkos();
        $update = false;
        if(!empty($type_invoice) && !empty($urutan)){
            $update = true;
            $item = SalesInvoiceItem::findOne(['no_invoice'=>$no_invoice, 'type_invoice'=>$type_invoice, 'urutan'=>$urutan]);
        }else{
            $item = SalesInvoiceItem::findOne(['no_invoice'=>$no_invoice]);
        }
        return json_encode(['data'=>$this->renderPartial('popup_biaya_lain', [
            'item'=>$item, 'typeOngkos'=>$typeOngkos, 'update'=>$update])
        ]);
    }

    public function actionUpdateHarga()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'UPDATE HARGA SUCCESSFULLY';
        if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $data = $request->post('SalesInvoiceItem');
                $salesInv = SalesInvoice::findOne(['no_invoice'=>$data['no_invoice']]);
                if(isset($salesInv)){
                    $salesDetail = SalesInvoiceDetail::findOne(['no_invoice'=>$data['no_invoice'], 'type_invoice'=>$data['type_invoice']]);
                    if(isset($salesDetail)){
                        $salesItem = SalesInvoiceItem::findOne(['no_invoice'=>$data['no_invoice'], 'type_invoice'=>$data['type_invoice'], 'urutan'=>$data['urutan']]);
                        if(isset($salesItem)){
                            if(!empty($data['new_harga_jual_1'])){
                                $new_harga_jual_1 = str_replace(',', '', $data['new_harga_jual_1']);
                                $salesItem->new_harga_jual_1 = $new_harga_jual_1;
                            }
                            if(!empty($data['new_harga_jual_2'])){
                                $new_harga_jual_2 = str_replace(',', '', $data['new_harga_jual_2']);
                                $salesItem->new_harga_jual_2 = $new_harga_jual_2;
                            }
                            $newTotalItem = $salesItem->newTotalOrder($salesItem);
                            $salesItem->new_total_order = $newTotalItem;
                            if($salesItem->save()){
                                $newTotalDetail = $salesDetail->newTotalOrder($salesDetail, $salesItem->proses_code);
                                if(!$salesDetail->save()){
                                    $success = false;
                                    foreach($salesDetail->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $newTotal = $salesInv->newTotalOrder($salesInv);
                                if(!$salesInv->save()){
                                    $success = false;
                                    foreach($salesInv->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                foreach($salesItem->errors as $error => $value){
                                    $message = $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'Data Sales Invoice Item tidak ditemukan.';
                        }
                    }else{
                        $success = false;
                        $message = 'Data Sales Invoice Detail tidak ditemukan.';
                    }
                }else{
                    $success = false;
                    $message = 'Data Sales Invoice tidak ditemukan.';
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
            $success = false;
            $message = 'The requested data does not exist.';
        }
        return \Yii::$app->session->setFlash(($success) ? 'success' : 'danger', $message);
    }

    public function actionUpdateBiayaLain()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE HARGA BIAYA LAIN SUCCESSFULLY';
        if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $data = $request->post('SalesInvoiceItem');
                $salesInv = SalesInvoice::findOne(['no_invoice'=>$data['no_invoice']]);
                if(isset($salesInv)){
                    $salesDetail = SalesInvoiceDetail::findOne(['no_invoice'=>$data['no_invoice'], 'type_invoice'=>3]);
                    if(empty($salesDetail)){
                        $salesDetail = new SalesInvoiceDetail();
                        $salesDetail->attributes = (array)$data;
                        $count = SalesInvoiceDetail::find()->where(['no_invoice'=>$data['no_invoice']])->count();
                        $salesDetail->urutan = $count +1;
                        // $salesDetail->total_biaya_lain = $salesDetail->grand_total = $salesDetail->new_grand_total = str_replace(',', '', $data['harga_jual_1']);
                        $salesDetail->total_biaya_lain = $salesDetail->grand_total = $salesDetail->new_grand_total = $salesDetail->harga_jual_1;
                        if($salesDetail->save()){
                            $salesItem = SalesInvoiceItem::findOne(['no_invoice'=>$data['no_invoice'], 'type_invoice'=>3, 'type_ongkos'=>$data['type_ongkos']]);
                            if(empty($salesItem)){
                                $salesItem = new SalesInvoiceItem();
                                $salesItem->attributes = (array)$data;
                                $salesItem->urutan = $salesItem->count +1;
                                $salesItem->harga_jual_1 = $salesItem->total_order = $salesItem->new_harga_jual_1 = $salesItem->new_total_order = $salesItem->harga_jual_1;
                                if($salesItem->save()){
                                    $salesInv->total_biaya_lain += $salesItem->total_order;
                                    $salesInv->new_grand_total = $salesInv->new_grand_total + $salesInv->total_biaya_lain;
                                    if(!$salesInv->save()){
                                        $success = false;
                                        foreach($salesInv->errors as $error => $value){
                                            $message = $value[0].', ';
                                        }
                                        $message = substr($message, 0, -2);
                                    }
                                }else{
                                    $success = false;
                                    foreach($salesItem->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                $message = 'Type Ongkos sudah ada.';
                            }
                        }else{
                            $success = false;
                            foreach($salesDetail->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }else{
                        if(!empty($data['urutan'])){
                            $salesItem = SalesInvoiceItem::findOne(['no_invoice'=>$data['no_invoice'], 'type_invoice'=>3, 'urutan'=>$data['urutan']]);
                            $oldTotalOrder = $salesItem->total_order;
                        }else{
                            $salesItem = new SalesInvoiceItem();
                            $salesItem->attributes = (array)$data;
                            $salesItem->urutan = $salesItem->count +1;
                            $salesItem->harga_jual_1 = $salesItem->total_order = $salesItem->new_harga_jual_1 = $salesItem->new_total_order = $salesItem->harga_jual_1;
                        }
                        // $salesDetail->attributes = (array)$data;
                        // $salesDetail->total_biaya_lain = $salesDetail->grand_total = $salesDetail->new_grand_total = str_replace(',', '', $data['harga_jual_1']);
                        if($salesDetail->save()){
                            print_r('A');die;
                            if(isset($salesItem)){
                                $oldTotalOrder = $salesItem->total_order;
                                $salesItem->attributes = (array)$data;
                                $salesItem->urutan = $salesItem->count +1;
                                $salesItem->harga_jual_1 = $salesItem->total_order = $salesItem->new_harga_jual_1 = $salesItem->new_total_order = $salesItem->harga_jual_1;
                                if($salesItem->save()){
                                    $salesInv->total_biaya_lain -= $oldTotalOrder;
                                    $salesInv->total_biaya_lain += $salesItem->total_order;
                                    $salesInv->new_grand_total = $salesInv->new_grand_total + $salesInv->total_biaya_lain;
                                    if(!$salesInv->save()){
                                        $success = false;
                                        foreach($salesInv->errors as $error => $value){
                                            $message = $value[0].', ';
                                        }
                                        $message = substr($message, 0, -2);
                                    }
                                }else{
                                    $success = false;
                                    foreach($salesItem->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                $message = 'Data Sales Item tidak ditemukan.';
                            }
                        }else{
                            $success = false;
                            foreach($salesDetail->errors as $error => $value){
                                $message = $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }

                    if($success){
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                }else{
                    $success = false;
                    $message = 'Data Sales Invoice tidak ditemukan.';
                }
            }catch(\Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }else{
            $success = false;
            $message = 'The requested data does not exist.';
        }
        return \Yii::$app->session->setFlash(($success) ? 'success' : 'danger', $message);
    }
}