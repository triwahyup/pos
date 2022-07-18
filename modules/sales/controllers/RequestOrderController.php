<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\LogsMail;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\pengaturan\models\PengaturanApproval;
use app\modules\sales\models\RequestOrder;
use app\modules\sales\models\RequestOrderApproval;
use app\modules\sales\models\RequestOrderItem;
use app\modules\sales\models\RequestOrderSearch;
use app\modules\sales\models\SalesOrder;
use app\modules\produksi\models\SpkOrder;
use app\modules\produksi\models\SpkOrderProses;
use app\modules\sales\models\TempRequestOrderItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * RequestOrderController implements the CRUD actions for RequestOrder model.
 */
class RequestOrderController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => [
                                'index', 'view', 'invoice', 'popup', 'temp-item', 'temp-bahan', 'get-temp', 
                                'list-item', 'search-item', 'autocomplete-item', 'select-item'
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'post', 'update-temp', 'send-approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[U]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[D]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['approval'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[A]')),
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
     * Lists all RequestOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequestOrder model.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_request)
    {
        $model = $this->findModel($no_request);
        $sendApproval = false;
        $postSpk = false;
        if($model->status_approval == 0 || $model->status_approval == 3){
            $sendApproval = true;
        }
        if($model->status_approval == 2 && ($model->post == 0 || empty($model->post))){
            $postSpk = true;
        }

        $typeApproval = false;
        $approval = RequestOrderApproval::findOne(['no_request'=>$no_request, 'status'=>2]);
        if(isset($approval)){
            if(($model->status_approval==1) && ($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                $typeApproval = true;
            }
        }

        return $this->render('view', [
            'model' => $model,
            'sendApproval' => $sendApproval,
            'postSpk' => $postSpk,
            'typeApproval' => $typeApproval,
        ]);
    }

    public function actionInvoice($no_request)
    {
        return $this->render('_invoice', [
            'model' => $this->findModel($no_request),
        ]);
    }

    /**
     * Creates a new RequestOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($no_so, $no_spk)
    {
        $success = true;
        $message = '';
        $model = new RequestOrder();
        $temp = new TempRequestOrderItem();
        $sorder = SalesOrder::findOne(['code' => $no_so]);
        $model->no_request = $model->generateCode();
        $model->no_so = $no_so;
        $model->no_spk = $no_spk;
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model = \Yii::$app->user->id;
                    $totalOrder = $model->totalOrder;
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $roItem = new RequestOrderItem();
                                $roItem->attributes = $temp->attributes;
                                $roItem->no_request = $model->no_request;
                                if(!$roItem->save()){
                                    $success = false;
                                    $message = (count($roItem->errors) > 0) ? 'ERROR CREATE REQUEST ORDER ITEM: ' : '';
                                    foreach($roItem->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE REQUEST ORDER: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE REQUEST ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_request.'] SUCCESS CREATE REQUEST ORDER.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_request' => $model->no_request]);
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
            'sorder' => $sorder,
            'temp' => $temp
        ]);
    }

    /**
     * Updates an existing RequestOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_request)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($no_request);
        $temp = new TempRequestOrderItem();
        $sorder = SalesOrder::findOne(['code' => $model->no_so]);
        if($this->request->isPost){
            if ($model->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->status_approval = null;
                    if($model->save()){
                        if(count($model->temps) > 0){
                            foreach($model->items as $empty)
                                $empty->delete();
                            foreach($model->temps as $temp){
                                $item = new RequestOrderItem();
                                $item->attributes = $temp->attributes;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR UPDATE REQUEST ORDER ITEM: ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE PO: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE REQUEST ORDER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->no_request.'] SUCCESS UPDATE REQUEST ORDER.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'no_request' => $model->no_request]);
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
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
                return $this->redirect(['index']);
            }else{
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di post SPK.');
                    return $this->redirect(['index']);
                }else{
                    $this->emptyTemp();
                    foreach($model->items as $detail){
                        $temp = new TempRequestOrderItem();
                        $temp->attributes = $detail->attributes;
                        $temp->user_id = \Yii::$app->user->id;
                        if(!$temp->save()){
                            $message = (count($temp->errors) > 0) ? 'ERROR LOAD REQUEST ORDER ITEM: ' : '';
                            foreach($temp->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                            \Yii::$app->session->setFlash('error', $message);
                        }
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'sorder' => $sorder,
            'temp' => $temp,
        ]);
    }

    /**
     * Deletes an existing RequestOrder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_request No Request
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_request)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_request);
        if(isset($model)){
            if($model->status_approval == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini masih dalam proses Approval.');
            }else{
                if($model->post == 1){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di post SPK.');
                }else{
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();
                    try{
                        $model->status = 0;
                        if($model->save()){
                            foreach($model->items as $item){
                                $item->status = 0;
                                if(!$item->save()){
                                    $success = false;
                                    $message = (count($item->errors) > 0) ? 'ERROR DELETE DETAIL REQUEST ORDER: ' : '';
                                    foreach($item->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR DELETE REQUEST ORDER: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }

                        if($success){
                            $transaction->commit();
                            $message = '['.$model->no_request.'] SUCCESS DELETE REQUEST ORDER.';
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
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the RequestOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_request No Request
     * @return RequestOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_request)
    {
        if (($model = RequestOrder::findOne($no_request)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    public function actionTempItem()
    {
        $temps = TempRequestOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['value'=>\Yii::$app->params['TYPE_KERTAS'], 'user_id'=> \Yii::$app->user->id])
            ->all();
        $model =  $this->renderAjax('_temp_item', ['temps'=>$temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionTempBahan()
    {
        $temps = TempRequestOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"')
            ->all();
        $model = $this->renderAjax('_temp_bahan', [
            'temps' => $temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempRequestOrderItem::find()
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
                $dataHeader = $request->post('RequestOrder');
                $no_request = (!empty($dataHeader['no_request'])) ? $dataHeader['no_request'] : 'tmp';
                // TEMP ITEM ATTRIBUTES
                $dataItem = $request->post('TempRequestOrderItem');
                $tempItem = new TempRequestOrderItem();
                $tempItem->attributes = (array)$dataItem;
                if(count($tempItem->itemBahan) > 0){
                    if(!empty($tempItem->bahan_qty)){
                        $tempItem->item_code = $tempItem->bahan_item_code;
                        $tempItem->supplier_code = $tempItem->bahan_supplier_code;
                        $tempItem->qty_order_1 = $tempItem->bahan_qty;
                    }else{
                        $success = false;
                        $message = 'Qty bahan pembantu tidak boleh kosong.';
                        $tempItem->qty_order_1 = null;
                    }
                }

                $tempItem->attributes = $tempItem->item->attributes;
                if(isset($tempItem->itemPricelist)){
                    $tempItem->attributes = $tempItem->itemPricelist->attributes;
                    $tempItem->attributes = $tempItem->satuan->attributes;
                    $tempItem->attributes = $tempItem->item->attributes;
                    $tempItem->no_request = $no_request;
                    $tempItem->urutan = $tempItem->countTemp +1;
                    $tempItem->total_order = $tempItem->totalOrder;
                    $tempItem->user_id = \Yii::$app->user->id;
                    if($tempItem->item->typeCode->value == \Yii::$app->params['TYPE_KERTAS']){
                        $tempItem->supplier_code = $dataItem['supplier_code'];
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
            $dataHeader = $request->post('RequestOrder');
            $dataItem = $request->post('TempRequestOrderItem');
            $tempItem = TempRequestOrderItem::findOne(['id'=>$dataItem['id']]);
            $no_request = $tempItem->no_request;
            $urutan = $tempItem->urutan;
            $supplierCode = $dataItem['supplier_code'];
            $tempItem->attributes = (array)$dataItem;
            $tempItem->attributes = $tempItem->item->attributes;
            if(isset($tempItem->itemPricelist)){
                $tempItem->attributes = $tempItem->itemPricelist->attributes;
                $tempItem->attributes = $tempItem->satuan->attributes;
                $tempItem->attributes = $tempItem->item->attributes;
                $tempItem->no_request = $no_request;
                $tempItem->urutan = $urutan;
                $tempItem->supplier_code = $supplierCode;
                $tempItem->total_order = $tempItem->totalOrder;
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
                if(!$tempItem->save()){
                    $success = false;
                    foreach($tempItem->errors as $error => $value){
                        $message = $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteTemp($id)
    {
        $success = true;
        $message = 'DELETE TEMP SUCCESSFULLY';
        $temp = TempRequestOrderItem::findOne(['id'=>$id]);
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

    protected function emptyTemp()
    {
        TempRequestOrderItem::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempRequestOrderItem::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_request_order_item AUTO_INCREMENT=1')->query();
        }
    }

    public function actionPopup()
    {
        $request = \Yii::$app->request;
        $approval = RequestOrderApproval::findOne(['no_request'=>$request->post('no_request'), 'status'=>2]);
        $model = $this->findModel($request->post('no_request'));
        if($request->post('type') == 'APPROVE'){
            return $this->renderPartial('_popup_approve', [
                'model' => $model,
                'approval' => $approval,
                'title' => 'Form Approve',
            ]);
        }
        if($request->post('type') == 'REJECT'){
            return $this->renderPartial('_popup_reject', [
                'model' => $model,
                'approval' => $approval,
                'title' => 'Form Reject',
            ]);
        }
    }

    public function actionSendApproval($no_request)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_request);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->status_approval = 1;
                if($model->save()){
                    $approvals = (new PengaturanApproval)->approval('request-order');
                    if(isset($approvals)){
                        $approvaln = RequestOrderApproval::findAll(['no_request'=>$no_request]);
                        if(count($approvaln) > 0)
                            RequestOrderApproval::deleteAll('no_request=:no_request', [':no_request'=>$no_request]);
                        foreach($approvals as $approval){
                            $app = new RequestOrderApproval();
                            $app->attributes = $approval->attributes;
                            $app->no_request = $no_request;
                            $app->status = 1;
                            if(!$app->save()){
                                $success = false;
                                foreach($app->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = 'Setting approval Request Order belum ada. Silakan hubungi administrator utk melakukan setting approval.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS REQUEST ORDER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $mailapproval = json_decode($this->mailapproval($model->no_request));
                    if($mailapproval->success){
                        $message = '['.$model->no_request.'] SUCCESS SEND APPROVAL REQUEST ORDER.';
                        $transaction->commit();
                        \Yii::$app->session->setFlash('success', $message);
                    }else{
                        $success = false;
                        $message = $mailapproval->message;
                    }
                }else{
                    $transaction->rollBack();
                }
            }catch(Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }else{
            $success = false;
            $message = 'Data Request Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }

    public function actionApproval()
    {
        $request = \Yii::$app->request;
        $data = $request->post('RequestOrderApproval');
        $success = true;
		$message = '';
        $model = $this->findModel($data['no_request']);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $approval = RequestOrderApproval::findOne(['no_request'=>$model->no_request, 'status'=>2]);
                if(isset($approval)){
                    if(($approval->user_id == \Yii::$app->user->id) || ($approval->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code)){
                        // APPROVE
                        if($data['type'] == 'APPROVE'){
                            $approval->comment = $data['comment'];
							$approval->status=3;
							if(empty($approval->user_id)){
								$approval->user_id = \Yii::$app->user->id;
							}
                            if($approval->save()){
                                $mailapproval = json_decode($this->mailapproval($model->no_request));
                                if($mailapproval->success){
                                    $is_akhir = true;
                                    if($mailapproval->akhir){
                                        $mailakhir = json_decode($this->mailapproval_akhir($model->no_request, $approval->comment));
                                        if($mailakhir->success){
                                            $model->status_approval=2;
                                            if(!$model->save()){
												$success = false;
												foreach($model->errors as $error => $value)
													$message .= strtoupper($value[0].', ');
												$message = substr($message, 0, -2);
											}
                                        }else{
                                            $is_akhir = false;
											$message = $mailakhir->message;
                                        }
                                    }
                                    if($is_akhir){
                                        $transaction->commit();
                                        $message = '['.$model->no_request.'] SUCCESS APPROVE REQUEST ORDER.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_request' => $model->no_request]);
                                    }else{
                                        $transaction->rollBack();
										$message = $mailapproval->message;
                                    }
                                }
                            }else{
                                $success = false;
								$message = (count($approval->errors) > 0) ? 'ERROR CREATE APPROVAL: ' : '';
								foreach($approval->errors as $error => $value){
									$message .= strtoupper($value[0].', ');
								}
								$message = substr($message, 0, -2);
                            }
                        }
                        // REJECTED
                        if($data['type'] == "REJECT"){
                            if(!empty($data['comment'])){
                                $approval->comment = $data['comment'];
								$approval->status=4;
								if(empty($approval->user_id)){
									$approval->user_id = \Yii::$app->user->id;
								}
                                if(!$approval->save()){
									$success = false;
									foreach($approval->errors as $error => $value)
										$message .= strtoupper($value[0].', ');
									$message = substr($message, 0, -2);
								}
                                $model->status_approval=3;
                                if(!$model->save()){
									$success = false;
									foreach($model->errors as $error => $value)
										$message .= strtoupper($value[0].', ');
									$message = substr($message, 0, -2);
								}

                                if($success){
                                    $mailakhir = json_decode($this->mailapproval_akhir($model->no_request, $approval->comment));
                                    if($mailakhir->success){
                                        $transaction->commit();
                                        $message = '['.$model->no_request.'] SUCCESS REJECT REQUEST ORDER.';
                                        \Yii::$app->session->setFlash('success', $message);
                                        return $this->redirect(['view', 'no_request' => $model->no_request]);
                                    }else{
                                        $success = false;
										$message .= $mailakhir->message;
                                    }
                                }else{
                                    $transaction->rollBack();
                                }
                            }else{
                                $success = false;
								$message = 'PLEASE, INSERT YOUR COMMENT.';
                            }
                        }
                    }else{
                        $success = false;
				        $message = 'YOU NOT ALLOWED TO APPROVE DOCUMENT.';
                    }
                }else{
                    $success = false;
					$message = 'APPROVAL NOT FOUND.';
                }
            }catch(Exception $e){
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
        }else{
            $success = false;
            $message = 'Data Purchase Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }

    function mailapproval($no_request)
    {
        $success = true;
		$message = '';
        $akhir = false;
		$urutan = 0;
		$profile = [];
        $approvals = RequestOrderApproval::find()->where(['no_request'=>$no_request])->orderBy(['urutan'=>SORT_ASC])->all();
        if(count($approvals) > 0){
            foreach($approvals as $approval){
                if(!$urutan){
					$profile = $approval->profile; // Function to call Profile User
				}
                if($approval->status == 1 && !$urutan && $success){
                    $urutan = $approval->urutan;
                }else if(($approval->status == 2 || $approval->status == 4) && (!$urutan)){
                    $success = false;
					$name = 'ANONIM';
                    if(!empty($approval->user_id)){
                        $name = $approval->profile->name;
                    }else{
                        $name = 'User Type: '. $approval->typeUser->value;
                    }

                    if($approval->status == 2){
						$message = 'Status Email is WAITING APPROVE for '. $name;
					}else{
						$message = 'Status Email is REJECT for '. $name;
					}
                }
            }
            if($urutan){
                $app = RequestOrderApproval::findOne(['no_request'=>$no_request, 'urutan'=>$urutan]);
                if(isset($app)){
                    $name = '';
                    if(!empty($app->user_id)){
						$name = 'Yth. Bpk/Ibu '. $app->profile[0]->name;
					}else{
						$name = 'Yth. Bpk/Ibu Divisi <b>'. $app->typeUser->value .'</b>';
					}

                    $emailuser = [];
					$str_mail = '';
                    foreach($profile as $user){
						if(!empty($user->email)){
							$emailuser[] = strtolower($user->email);
							$str_mail .= strtolower($user->email).', ';
						}
					}
                    if(count($emailuser) > 0){
                        $body = $this->renderPartial('_mailapproval', [
                            'approval' => $app,
							'name' => $name,
                            'url' => \Yii::$app->params['URL'].'/sales/request-order/view&no_request='.$approval->no_request,
                        ]);
                        
                        $logs_mail = new LogsMail();
                        $logs_mail->type = 'APPROVAL REQUEST ORDER';
                        $logs_mail->email = substr($str_mail, 0, -2);
                        $logs_mail->bcc = '';
                        $logs_mail->subject = 'Approval Request Order '. $app->no_request;
						$logs_mail->body = $body;
						$logs_mail->keterangan = '';
                        
                        $sendMail = \Yii::$app->mailer->compose()
                            ->setFrom(['pos@ptmma.co.id' => 'Notification Reminder Approval'])
                            ->setTo($logs_mail->email)
                            ->setSubject($logs_mail->subject)
                            ->setHtmlBody($body);
                        if($sendMail->send()){
                            $logs_mail->status = 1;
                            if(!$logs_mail->save()){
                                $success = false;
                                foreach($logs_mail->errors as $error=>$value){
                                    $message .= strtoupper($error).": ".$value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
    
                            $app->status=2;
                            if(!$app->save()){
                                $success = false;
                                foreach($app->errors as $error=>$value){
                                    $message .= strtoupper($error).": ".$value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'Gagal Send Email. Coba Sesaat Lagi.';
                        }
                    }else{
                        $success = false;
						$message = 'Email user to approval is EMPTY';
                    }
                }else{
                    $success = false;
					$message = 'Pengaturan Approval belum di setting.';
                }
            }else{
                $akhir = true;
            }
        }else{
            $success = false;
			$message = 'Pengaturan Approval belum di setting.';
        }
        return json_encode(['success'=>$success, 'message'=>$message, 'akhir'=>$akhir]);
    }

    function mailapproval_akhir($no_request, $comment=NULL)
    {
        $success = true;
		$message = '';
        $approval = RequestOrderApproval::find()
            ->where(['no_request'=>$no_request])
            ->orderBy(['urutan'=>SORT_DESC])
            ->one();
        $str_mail = '';
        if(isset($approval)){
            $body = $this->renderPartial('_mailapproval_akhir', [
                'approval' => $approval,
				'description' => $comment,
                'url' => \Yii::$app->params['URL'].'/sales/request-order/view&no_request='.$approval->no_request,
            ]);

            $logs_mail = new LogsMail();
            $logs_mail->type = 'APPROVAL REQUEST ORDER';
            $logs_mail->email = (isset($approval->request->profile)) ? $approval->request->profile->email : '';
            $logs_mail->bcc = '';
            $logs_mail->subject = 'Approval Request Order '. $approval->no_request;
            $logs_mail->body = $body;
            $logs_mail->keterangan = '';
            
            $sendMail = \Yii::$app->mailer->compose()
                ->setFrom(['pos@ptmma.co.id' => 'Notification Reminder Approval'])
                ->setTo($logs_mail->email)
                ->setSubject($logs_mail->subject)
                ->setHtmlBody($body);
            if($sendMail->send()){
                $logs_mail->status = 1;
                if(!$logs_mail->save()){
                    $success = false;
                    foreach($logs_mail->errors as $error=>$value){
                        $message .= strtoupper($error).": ".$value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
            }else{
                $success = false;
                $message = 'Gagal Send Email. Coba Sesaat Lagi.';
            }
        }else{
            $success = false;
			$message = 'Pengaturan Approval belum di setting.';
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionPost($no_request)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_request);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->post=1;
                if($model->save()){
                    // PROSES KURANG STOK
                    $stock = 0;
                    $is_material = false;
                    foreach($model->items as $val){
                        $stockItem = $val->inventoryStock;
                        if(isset($stockItem)){
                            $stock = $stockItem->satuanTerkecil($val->item_code, [
                                0=>$val->qty_order_1,
                                1=>$val->qty_order_2
                            ]);
                            if($stockItem->onhand >= $stock){
                                if(isset($val->type)){
                                    if($val->type->value == \Yii::$app->params['TYPE_KERTAS']){
                                        $is_material = true;
                                    }
                                }

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
                                $stockTransaction->no_document = $model->no_request;
                                $stockTransaction->tgl_document = $model->tgl_request;
                                $stockTransaction->type_document = "REQUEST ORDER";
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
                    // PROSES SIMPAN SPK ORDER
                    if($is_material){
                        $spkOrder = new SpkOrder();
                        $spkOrder->attributes = $model->salesOrder->attributes;
                        $spkOrder->attributes = $model->attributes;
                        $spkOrder->no_spk = $spkOrder->generateCode();
                        $spkOrder->tgl_spk = date('Y-m-d');
                        $spkOrder->up_produksi = 0;
                        $spkOrder->total_warna = 0;
                        $spkOrder->total_qty = 0;
                        $spkOrder->total_qty_up = 0;
                        if($spkOrder->save()){
                            $dataPotong = [];
                            if($model->salesOrder){
                                foreach($model->salesOrder->potongs as $val){
                                    $uk_potong = $val->lebar.'x'.$val->panjang;
                                    $dataPotong[$val->item_code][$val->supplier_code][$uk_potong] = [
                                        'potong_id' => $val->urutan,
                                        'uk_potong' => $uk_potong,
                                    ];
                                }
                            }

                            $uid = 1;
                            foreach($model->itemsMaterial as $val){
                                $stock = $val->inventoryStock->satuanTerkecil($val->item_code, [
                                    0=>$val->qty_order_1,
                                    1=>$val->qty_order_2,
                                ]);
                                foreach($model->salesOrder->proses as $val_proses){
                                    foreach($dataPotong[$val->item_code][$val->supplier_code] as $val_uk){
                                        $spkProses = new SpkOrderProses();
                                        $spkProses->attributes = $val->attributes;
                                        $spkProses->attributes = (array)$val_uk;
                                        $spkProses->no_spk = $spkOrder->no_spk;
                                        $spkProses->proses_id = $uid++;
                                        $spkProses->mesin_type = $val_proses->mesin_type;
                                        $spkProses->proses_code = $val_proses->proses_code;
                                        $spkProses->proses_type = $val_proses->type;
                                        $spkProses->qty_proses = $stock;
                                        $spkProses->gram = (isset($val->item)) ? $val->item->gram : NULL;
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
                            }
                        }else{
                            $success = false;
                            $message = (count($spkOrder->errors) > 0) ? 'ERROR CREATE SPK: ' : '';
                            foreach($spkOrder->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST REQUEST ORDER TO SPK: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->no_request.'] SUCCESS POST REQUEST ORDER TO SPK.';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'no_request' => $model->no_request]);
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
            $message = 'Data Request Order not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_request' => $model->no_request]);
    }
}