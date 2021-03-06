<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterSatuan;
use app\modules\produksi\models\SpkPotongRoll;
use app\modules\produksi\models\SpkPotongRollDetail;
use app\modules\produksi\models\SpkPotongRollSearch;
use app\modules\produksi\models\TempSpkPotongRollDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * PotongRollController implements the CRUD actions for SpkPotongRoll model.
 */
class PotongRollController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-item', 'search', 'autocomplete', 'item', 'temp', 'get-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'post'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[U]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[D]')),
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
     * Lists all SpkPotongRoll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpkPotongRollSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpkPotongRoll model.
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
     * Creates a new SpkPotongRoll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $temp = new TempSpkPotongRollDetail();
        $model = new SpkPotongRoll();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->code = $model->generateCode();
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new SpkPotongRollDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->code = $model->code;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE POTONG MATERIAL ROLL DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE POTONG MATERIAL ROLL: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE POTONG MATERIAL ROLL: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS CREATE POTONG MATERIAL ROLL.';
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
     * Updates an existing SpkPotongRoll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $success = true;
        $message = '';
        $temp = new TempSpkPotongRollDetail();
        $model = $this->findModel($code);
        $model->item_name = (isset($model->item)) ? $model->item->name : null;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if($model->save()){
                    if(count($model->temps) > 0){
                        foreach($model->details as $empty)
                            $empty->delete();
                        foreach($model->temps as $temp){
                            $detail = new SpkPotongRollDetail();
                            $detail->attributes = $temp->attributes;
                            if(!$detail->save()){
                                $success = false;
                                $message = (count($detail->errors) > 0) ? 'ERROR UPDATE POTONG MATERIAL ROLL DETAIL: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = 'ERROR UPDATE POTONG MATERIAL ROLL: DETAIL IS EMPTY.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE POTONG MATERIAL ROLL: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $this->emptyTemp();
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS UPDATE POTONG MATERIAL ROLL.';
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
        }else{
            if($model->post == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini tidak bisa di Edit (Sudah Post ke Gudang Stock Item).');
                return $this->redirect(['index']);
            }else{
                $this->emptyTemp();
                foreach($model->details as $detail){
                    $temp = new TempSpkPotongRollDetail();
                    $temp->attributes = $detail->attributes;
                    $temp->user_id = \Yii::$app->user->id;
                    if(!$temp->save()){
                        $message = (count($temp->errors) > 0) ? 'ERROR LOAD POTONG MATERIAL ROLL DETAIL: ' : '';
                        foreach($temp->errors as $error => $value){
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
            'temp' => $temp,
        ]);
    }

    /**
     * Deletes an existing SpkPotongRoll model.
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
            if($model->post == 1){
                \Yii::$app->session->setFlash('error', 'Dokumen ini tidak bisa di Hapus (Sudah Post ke Gudang Stock Item).');
            }else{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->item_name = $model->item->name;
                    $model->status = 0;
                    if($model->save()){
                        foreach($model->details as $detail){
                            $detail->status = 0;
                            if(!$detail->save()){
                                $success = false;
                                $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL POTONG MATERIAL ROLL: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR DELETE POTONG MATERIAL ROLL: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
    
                    if($success){
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS DELETE POTONG MATERIAL ROLL.';
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
        return $this->redirect(['index']);
    }

    /**
     * Finds the SpkPotongRoll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return SpkPotongRoll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = SpkPotongRoll::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListItem()
    {
        $model = InventoryStockItem::find()
            ->alias('a')
            ->select(['item_code', 'b.name as item_name', 'c.name as satuan_name', 'd.name as supplier_name'])
            ->leftJoin('master_material  b', 'b.code = a.item_code')
            ->leftJoin('master_satuan c', 'c.code = b.satuan_code')
            ->leftJoin('master_person d', 'd.code = a.supplier_code')
            ->where(['c.name'=>\Yii::$app->params['TYPE_ROLL'], 'a.status'=>1])
            ->orderBy(['item_code'=>SORT_ASC])
            ->asArray()
            ->limit(10)
            ->all();
        
        return json_encode(['data'=>$this->renderPartial('_list_item', [
            'model'=>$model])
        ]);
    }

    public function actionAutocomplete()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = InventoryStockItem::find()
                ->alias('a')
                ->select(['concat(b.code,"-", b.name, " (", d.name, ")") as label', 
                    'item_code', 'b.name as item_name', 'c.name as satuan_name', 'd.name as supplier_name'])
                ->leftJoin('master_material  b', 'b.code = a.item_code')
                ->leftJoin('master_satuan c', 'c.code = b.satuan_code')
                ->leftJoin('master_person d', 'd.code = a.supplier_code')
                ->where(['c.name'=>\Yii::$app->params['TYPE_ROLL'], 'a.status'=>1])
                ->andWhere('concat(b.code,"-", b.name, " (", d.name, ")") LIKE "%'.$_POST['search'].'%"')
                ->orderBy(['item_code'=>SORT_ASC])
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
            $model = InventoryStockItem::find()
                ->alias('a')
                ->select(['item_code', 'b.name as item_name', 'c.name as satuan_name', 'd.name as supplier_name'])
                ->leftJoin('master_material  b', 'b.code = a.item_code')
                ->leftJoin('master_satuan c', 'c.code = b.satuan_code')
                ->leftJoin('master_person d', 'd.code = a.supplier_code')
                ->where(['c.name'=>\Yii::$app->params['TYPE_ROLL'], 'a.status'=>1])
                ->orderBy(['item_code'=>SORT_ASC])
                ->asArray()
                ->limit(10)
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_item', [
            'model'=>$model])
        ]);
    }

    public function actionItem()
    {
        $model = InventoryStockItem::find()
            ->alias('a')
            ->select(['a.*', 'b.*',
                'b.name as item_name', 'c.name as material', 'd.name as satuan', 'e.name as supplier'])
            ->leftJoin('master_material  b', 'b.code = a.item_code')
            ->leftJoin('master_kode c', 'c.code = b.material_code')
            ->leftJoin('master_satuan d', 'd.code = b.satuan_code')
            ->leftJoin('master_person e', 'e.code = a.supplier_code')
            ->where(['item_code'=>$_POST['code'], 'a.status'=>1])
            ->orderBy(['item_code'=>SORT_ASC])
            ->asArray()
            ->one();

        $stock = InventoryStockItem::findOne(['item_code'=>$model['item_code'], 'supplier_code'=>$model['supplier_code']]);
        $model['PxL'] = $model['panjang'] .' x '. $model['lebar'];
        $model['panjang'] = 0;
        $model['stock'] = $stock->konversi($stock->item, $stock->onhand);
        
        $data = $this->renderAjax('_stock', ['model'=>$model]);
        return json_encode(['data'=>$data, 'model'=>$model]);
    }

    public function actionTemp()
    {
        $temps = TempSpkPotongRollDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $model =  $this->renderAjax('_temp', ['temps'=>$temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempSpkPotongRollDetail::find()->where(['id'=>$id])->asArray()->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $dataHeader = $request->post('SpkPotongRoll');
            if(!$dataHeader['panjang']){
                $success = false;
                $message = 'Panjang tidak boleh kosong.';
            }else if(!$dataHeader['lebar']){
                $success = false;
                $message = 'Lebar tidak boleh kosong.';
            }else if(!$dataHeader['potong']){
                $success = false;
                $message = 'Total potong tidak boleh kosong.';
            }else{
                $dataTemp = $request->post('TempSpkPotongRollDetail');
                if(!$dataTemp['panjang'] || !$dataTemp['lebar']){
                    $success = false;
                    $message = 'Potong (PxL) tidak boleh kosong.';
                }else if(!$dataTemp['gram']){
                    $success = false;
                    $message = 'Gram tidak boleh kosong.';
                }else if(!$dataTemp['first_name']){
                    $success = false;
                    $message = 'Nama Depan tidak boleh kosong.';
                }else{
                    $temp = new TempSpkPotongRollDetail();
                    $temp->attributes = (array)$dataHeader;
                    if($temp->count < $dataHeader['potong']){
                        $temp->attributes = (array)$dataTemp;
                        $temp->code = (!empty($temp->code)) ? $temp->code : 'tmp';
                        $checkUkPotong = $temp->checkUkPotong($temp);
                        if($checkUkPotong['success']){
                            $checkPanjang = $temp->checkPanjang($dataHeader['panjang'], $dataTemp['panjang']);
                            if($checkPanjang['success']){
                                $temp->name = $temp->newItemName();
                                $temp->urutan = $temp->count +1;
                                $temp->user_id = \Yii::$app->user->id;
                                $pembagian = $temp->item->lebar / $dataTemp['lebar'];
                                $hasilQTY = $checkPanjang['hPanjang'] / $checkPanjang['tPanjang'];
                                if($pembagian == 2){
                                    $temp->qty = floor($hasilQTY * $pembagian);
                                }else{
                                    $temp->qty = floor($hasilQTY);
                                }
                                if(!$temp->save()){
                                    $success = false;
                                    foreach($temp->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                $message = 'Panjang tidak boleh lebih dari '.$checkPanjang['hPanjang'];
                            }
                        }else{
                            $success = false;
                            $message = 'Lebar tidak boleh lebih dari sisa potong. Sisa potong '.$checkUkPotong['sisa_potong'];
                        }
                    }else{
                        $success = false;
                        $message = 'Maksimal Detail Potong '.$dataHeader['potong'];
                    }
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
        $temp = TempSpkPotongRollDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempSpkPotongRollDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempSpkPotongRollDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_spk_potong_roll_detail AUTO_INCREMENT=1')->query();
        }
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
                $model->item_name = $model->item->name;
                $model->post=1;
                if($model->save()){
                    foreach($model->details as $val){
                        // CREATE NEW MATERIAL
                        $material = MasterMaterial::findOne(['name'=>$val->name, 'status'=>1]);
                        if(empty($material)){
                            $material = new MasterMaterial();
                            $material->attributes = $model->attributes;
                            $material->attributes = $val->attributes;
                            $material->code = $material->generateCode($model->type_code);
                            
                            $satuan = MasterSatuan::findOne(['name'=>\Yii::$app->params['TYPE_RIM_PLANO'], 'status'=>1]);
                            $material->satuan_code = $satuan->code;
                            if(!$material->save()){
                                $success = false;
                                $message = (count($material->errors) > 0) ? 'ERROR CREATE NEW MASTER MATERIAL: ' : '';
                                foreach($material->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        
                        // STOCK OUT
                        {
                            $stockItem = InventoryStockItem::findOne(['item_code'=>$val->item_code, 'supplier_code'=>$val->supplier_code]);
                            $stockItem->attributes = $val->attributes;
                            $stockItem->onhand = $stockItem->onhand-$model->panjang;
                            $stockItem->onsales = $stockItem->onsales+$model->panjang;
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
                            $stockTransaction->tgl_document = $model->date;
                            $stockTransaction->type_document = "ROLL MATERIAL";
                            $stockTransaction->status_document = "OUT";
                            $stockTransaction->qty_out = $model->panjang;
                            if(!$stockTransaction->save()){
                                $success = false;
                                $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                foreach($stockTransaction->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        // END STOCK OUT
                        
                        // STOCK IN
                        {
                            $newItem = $material->code;
                            $stockItem = InventoryStockItem::findOne(['item_code'=>$newItem, 'supplier_code'=>$val->supplier_code]);
                            if(empty($stockItem)){
                                $stockItem = new InventoryStockItem();
                            }
                            $stockItem->item_code = $newItem;
                            $stockItem->supplier_code = $val->supplier_code;
                            $stockItem->onhand = $stockItem->onhand+$val->qty;
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
                            $stockTransaction->tgl_document = $model->date;
                            $stockTransaction->type_document = "ROLL MATERIAL";
                            $stockTransaction->status_document = "IN";
                            $stockTransaction->qty_in = $val->qty;
                            if(!$stockTransaction->save()){
                                $success = false;
                                $message = (count($stockTransaction->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                foreach($stockTransaction->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        // END STOCK IN
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST ROLL MATERIAL TO STOCK GUDANG ITEM: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->code.'] SUCCESS POST ROLL MATERIAL TO STOCK GUDANG ITEM.';
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
            $message = 'Data Potong Roll not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'code' => $model->code]);
    }
}
