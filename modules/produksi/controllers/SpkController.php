<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMaterialItemPricelist;
use app\modules\master\models\MasterMesin;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkDetail;
use app\modules\produksi\models\SpkDetailBahan;
use app\modules\produksi\models\SpkDetailProses;
use app\modules\produksi\models\SpkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SpkController implements the CRUD actions for Spk model.
 */
class SpkController extends Controller
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
                                'index', 'view', 'autocomplete',
                                'list-bahan', 'search-bahan', 'item-bahan', 'create-bahan', 'delete-bahan',
                                'lock-bahan', 'lock-proses',
                                'list-mesin', 'create-proses', 'delete-proses',
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('spk')),
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
     * Lists all Spk models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Spk model.
     * @param string $no_spk No Spk
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_spk)
    {
        $model = $this->findModel($no_spk);
        $spkBahan = new SpkDetailBahan();
        $spkProses = new SpkDetailProses();
        $spkDetail = SpkDetail::findOne(['no_spk'=>$no_spk]);
        
        return $this->render('view', [
            'model' => $model,
            'spkBahan' => $spkBahan,
            'spkProses' => $spkProses,
            'spkDetail' => $spkDetail,
        ]);
    }

    /**
     * Finds the Spk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_spk No Spk
     * @return Spk the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_spk)
    {
        if (($model = Spk::findOne($no_spk)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAutocomplete()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = MasterMaterialItem::find()
                ->select(['code', 'concat(code,"-",name) label', 'concat(code,"-",name) name'])
                ->where(['type_code'=>\Yii::$app->params['MATERIAL_BP_CODE'], 'status'=>1])
                ->andWhere('concat(code,"-",name) LIKE "%'.$_POST['search'].'%"')
                ->asArray()
                ->limit(10)
                ->all();
        }
        return  json_encode($model);
    }

    public function actionListBahan()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.onhand', 'c.name satuan', 'd.name material'])
            ->leftJoin('inventory_stock_item b', 'b.item_code = a.code')
            ->leftJoin('master_satuan c', 'c.code = a.satuan_code')
            ->leftJoin('master_material d', 'd.code = a.material_code')
            ->where(['a.type_code'=>\Yii::$app->params['MATERIAL_BP_CODE'], 'a.status'=>1])
            ->orderBy(['a.code'=>SORT_ASC])
            ->limit(10)
            ->asArray()
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_bahan', ['model'=>$model])]);
    }
    
    public function actionSearchBahan()
    {
        $model = [];
        if(isset($_POST['code'])){
            $model = MasterMaterialItem::find()
                ->alias('a')
                ->select(['a.*', 'b.onhand', 'c.name satuan', 'd.name material'])
                ->leftJoin('inventory_stock_item b', 'b.item_code = a.code')
                ->leftJoin('master_satuan c', 'c.code = a.satuan_code')
                ->leftJoin('master_material d', 'd.code = a.material_code')
                ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
                ->asArray()
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_bahan', ['model'=>$model])]);
    }

    public function actionItemBahan()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select([
                'a.*',
                'a.code as item_bahan_code',
                'a.name as item_bahan_name',
                'b.*',
                'c.code as type_bahan_code',
                'c.name as type_bahan'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->leftJoin('master_material c', 'c.code = a.material_code and c.type_code = a.type_code')
            ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
            ->asArray()
            ->one();
        return json_encode($model);
    }

    public function actionCreateBahan()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('SpkDetailBahan');
            if(!empty($data['item_name']) && !empty($data['item_bahan_name'])){
                $stockItem = InventoryStockItem::findOne(['item_code'=>$data['item_bahan_code']]);
                if($stockItem->onhand > 0){
                    if(!empty($data['qty_1']) || !empty($data['qty_2'])){
                        $connection = \Yii::$app->db;
                        $transaction = $connection->beginTransaction();
                        try{
                            $model = SpkDetailBahan::findOne(['no_spk'=>$data['no_spk'], 'item_code'=>$data['item_code'], 'item_bahan_code'=>$data['item_bahan_code']]);
                            $urutan = SpkDetailBahan::find()->where(['no_spk'=>$data['no_spk'], 'item_code'=>$data['item_code']])->count();
                            if(isset($model)){
                                $model->qty_1 = $model->qty_1 + $data['qty_1'];
                                $model->qty_2 = $model->qty_2 + $data['qty_2'];
                                $model->tgl_spk = $data['tgl_spk'];
                            }else{
                                $pricelist = MasterMaterialItemPricelist::findOne(['item_code'=>$data['item_bahan_code'], 'status_active'=>1]);
                                if(isset($pricelist)){
                                    $model = new SpkDetailBahan();
                                    $model->attributes = (array)$data;
                                    $model->attributes = $model->itemBahan->attributes;
                                    $model->attributes = $pricelist->attributes;
                                    $model->item_code = $data['item_code'];
                                    $model->satuan_bahan_code = $model->itemBahan->satuan_code;
                                    $model->urutan = $urutan +1;
                                }else{
                                    $success = false;
                                    $message = 'Pricelist tidak ditemukan / masih kosong.';
                                }
                            }
                            
                            if($success){
                                if($model->save()){
                                    // PROSES KURANG STOK
                                    $stock = $stockItem->satuanTerkecil($model->item_bahan_code, [
                                        0=>$data['qty_1'],
                                        1=>$data['qty_2'],
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
                                        $stockTransaction->no_document = $model->no_spk;
                                        $stockTransaction->tgl_document = $model->tgl_spk;
                                        $stockTransaction->type_document = "SPK";
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
                                        $message = 'SISA STOCK ITEM '.$model->item_code.' TIDAK MENCUKUPI. SISA '.$stockItem->onhand;
                                    }
                                }else{
                                    $success = false;
                                    $message = (count($model->errors) > 0) ? 'ERROR CREATE SPK BAHAN: ' : '';
                                    foreach($model->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }

                            if($success){
                                $message = '['.$model->no_spk.'-'.$model->urutan.'] SUCCESS CREATE SPK BAHAN.';
                                $transaction->commit();
                                $logs =	[
                                    'type' => Logs::TYPE_USER,
                                    'description' => $message,
                                ];
                                Logs::addLog($logs);
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
                        $message = 'QTY kosong.';
                    }
                }else{
                    $success = false;
                    $message = 'Stock tidak mencukupi. Sisa stock '.$stockItem->onhand;
                }
            }else{
                $success = false;
                $message = 'Material dan Bahan wajib diisi.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteBahan()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $noSpk = $request->post('no_spk');
            $urutan = $request->post('urutan');
            $itemCode = $request->post('item_code');
            $itemBahanCode = $request->post('item_bahan_code');
            $prosesSpk = $this->findModel($noSpk);
            if($prosesSpk->status_produksi == 1){
                $model = SpkDetailBahan::findOne(['no_spk'=>$noSpk, 'urutan'=>$urutan, 'item_code'=>$itemCode, 'item_bahan_code'=>$itemBahanCode]);
                if(isset($model)){
                    $connection = \Yii::$app->db;
                    $transaction = $connection->beginTransaction();
                    try{
                        if($model->delete()){
                            foreach($model->datas as $index=>$val){
                                $val->urutan = $index +1;
                                if(!$val->save()){
                                    $success = false;
                                    foreach($val->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }

                            // KEMBALIKAN STOK
                            $stockItem = InventoryStockItem::findOne(['item_code'=>$itemBahanCode, 'status'=>1]);
                            if(isset($stockItem)){
                                $stock = $stockItem->satuanTerkecil($model->item_bahan_code, [
                                    0=>$model->qty_1,
                                    1=>$model->qty_2,
                                ]);
                                $stockItem->onhand = $stockItem->onhand+$stock;
                                $stockItem->onsales = $stockItem->onsales-$stock;
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
                                $stockTransaction->no_document = $model->no_spk;
                                $stockTransaction->tgl_document = date('Y-m-d');
                                $stockTransaction->type_document = "ROLLBACK SPK";
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
                                $message = 'Item {'.$model->item_code.'} tidak ditemukan di Inventory Stock Item';
                            }
                        }else{
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR DELETE SPK BAHAN: ' : '';
                            foreach($model->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
    
                        if($success){
                            $message = '['.$model->no_spk.'-'.$model->urutan.'] SUCCESS DELETE SPK BAHAN.';
                            $transaction->commit();
                            $logs =	[
                                'type' => Logs::TYPE_USER,
                                'description' => $message,
                            ];
                            Logs::addLog($logs);
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
                    $message = 'DATA NOT FOUND.';
                }
            }else{
                $success = false;
                $message = 'SPK SUDAH DI PROSES. TIDAK BISA MENGEMBALIKAN STOK.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionLockBahan($no_spk)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_spk);
        if(isset($model)){
            if(count($model->detailsBahan) > 0){
                $model->status_produksi=2;
                if($model->save()){
                    \Yii::$app->session->setFlash('success', 'NO. SPK '.$no_spk.' BERHASIL DI PROSES.');
                }else{
                    $success = false;
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }
            }else{
                $success = false;
                $message = 'Data SPK Bahan masih kosong.';
            }
        }else{
            $success = false;
            $message = 'Data SPK not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_spk' => $model->no_spk]);
    }

    public function actionLockProses($no_spk)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_spk);
        if(isset($model)){
            $spkProses = SpkDetailProses::findOne(['no_spk'=>$no_spk, 'status_proses'=>1]);
            if(isset($spkProses)){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->status_produksi=3;
                    if($model->save()){
                        foreach($model->detailsProses as $detail){
                            if($detail->status_proses == 1){
                                $detail->status_proses = 2;
                                if(!$detail->save()){
                                    $success = false;
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }
                    }else{
                        $success = false;
                        foreach($model->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
    
                    if($success){
                        $transaction->commit();
                        \Yii::$app->session->setFlash('success', 'NO. SPK '.$no_spk.' BERHASIL DI PROSES.');
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
                $message = 'Data SPK Proses masih kosong.';
            }
        }else{
            $success = false;
            $message = 'Data SPK not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'no_spk' => $model->no_spk]);
    }

    public function actionListMesin($no_spk, $item_code, $type)
    {
        $keterangan = 'Keterangan: ';
        $sisaProses = 0;
        $stockItem = InventoryStockItem::findOne(['item_code'=>$item_code, 'status'=>1]);
        if(isset($stockItem)){
            $spkDetail = SpkDetail::findOne(['no_spk'=>$no_spk, 'item_code'=>$item_code]);
            $qtyProses = 0;
            if(count($spkDetail->detailsProses($type)) > 0){
                foreach($spkDetail->detailsProses($type) as $val){
                    $qtyProses += $val->qty_proses;
                }
            }
            
            // POTONG
            if($type == 1){
                $qtyOrder = $stockItem->satuanTerkecil($spkDetail->item_code, [
                    0=>$spkDetail->qty_order_1,
                    1=>$spkDetail->qty_order_2,
                ]);
                $konversi = $stockItem->konversi($spkDetail->item_code, $qtyOrder);
                $sisaProses = $qtyOrder-$qtyProses;
                $keterangan .= 'Order '.$konversi.' ('.$qtyOrder.' LB). Dipotong menjadi '.$spkDetail->total_potong.'. Sisa yang belum di proses '.$sisaProses.' LB.';
            }
            // CETAK
            else if($type == 2){
                $sisaProses = $spkDetail->jumlah_cetak-$qtyProses;
                $keterangan .= 'Total cetak '.$spkDetail->jumlah_cetak.' LB. Sisa yang belum di proses '.$sisaProses.' LB.';
            }
            // POND
            else if($type == 3){
                $sisaProses = $spkDetail->jumlah_objek-$qtyProses;
                $keterangan .= 'Total cetak '.$spkDetail->jumlah_objek.' LB. Sisa yang belum di proses '.$sisaProses.' LB.';
            }
        }
        
        $andWhere = '';
        if($type == 1){            
            $andWhere = 'type_code="014"';
        }else if($type == 2){
            $andWhere = 'type_code="012"';
        }else if($type == 3){
            $andWhere = 'type_code="013"';
        }else if($type == 5){
            $andWhere = 'type_code="015"';
        }else{
            $andWhere = 'type_code IN("016","017")';
        }
        
        $mesin = MasterMesin::find()
            ->select(['name as name', 'code as code'])
            ->where(['status'=>1])
            ->andWhere($andWhere)
            ->asArray()
            ->all();
        return json_encode(['mesin'=>$mesin, 'keterangan'=>$keterangan, 'sisa_proses'=>$sisaProses]);
    }

    public function actionCreateProses()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('SpkDetailProses');
            
            $spkDetail = SpkDetail::findOne(['no_spk'=>$data['no_spk'], 'item_code'=>$data['item_code']]);
            if(isset($spkDetail)){
                $qtyProses = 0;
                if(count($spkDetail->detailsProses($data['type_proses'])) > 0){
                    foreach($spkDetail->detailsProses($data['type_proses']) as $val){
                        $qtyProses += $val->qty_proses;
                    }
                }
                
                $totalQTY = 0;
                // POTONG
                if($data['type_proses'] == 1){
                    $stockItem = InventoryStockItem::findOne(['item_code'=>$spkDetail->item_code]);
                    $qtyOrder = 0;
                    if(isset($stockItem)){
                        $qtyOrder = $stockItem->satuanTerkecil($spkDetail->item_code, [
                            0=>$spkDetail->qty_order_1,
                            1=>$spkDetail->qty_order_2,
                        ]);
                    }
                    $totalQTY = $qtyOrder-$qtyProses;
                }
                // CETAK
                else if($data['type_proses'] == 2){
                    $totalQTY = $spkDetail->jumlah_cetak-$qtyProses;
                }
                // POND
                else if($data['type_proses'] == 3){
                    $totalQTY = $spkDetail->jumlah_objek-$qtyProses;
                }
                
                if(!$totalQTY == 0){
                    if(!$data['qty_proses'] == 0){
                        if($totalQTY >= $data['qty_proses']){
                            $filter = [
                                'no_spk'=>$data['no_spk'],
                                'item_code'=>$data['item_code'],
                                'mesin_code'=>$data['mesin_code'],
                                'type_proses'=>$data['type_proses']
                            ];
                            $spkProses = SpkDetailProses::findOne($filter);
                            if(empty($spkProses)){
                                $spkProses = new SpkDetailProses();
                                $spkProses->attributes = (array)$data;
                                $spkProses->urutan = count($spkDetail->detailsProses) +1;
                                $spkProses->mesin_type_code = $spkProses->mesin->type_code;
                                $spkProses->status_proses = 1; // IN PROGRESS
                                if($spkProses->save()){
                                    $message = '['.$spkProses->no_spk.'-'.$spkProses->urutan.'] SUCCESS CREATE SPK PROSES.';
                                    $logs =	[
                                        'type' => Logs::TYPE_USER,
                                        'description' => $message,
                                    ];
                                    Logs::addLog($logs);
                                }else{
                                    $success = false;
                                    $message = (count($spkProses->errors) > 0) ? 'ERROR CREATE SPK PROSES: ' : '';
                                    foreach($spkProses->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                $message = 'MESIN '.$spkProses->mesin->name.' SUDAH DIGUNAKAN.';
                            }
                        }else{
                            $success = false;
                            $message = 'QTY yang akan diproses terlalu besar. Maksimal input proses '.$totalQTY;
                        }
                    }else{
                        $success = false;
                        $message = 'QTY tidak boleh 0.';
                    }
                }else{
                    $success = false;
                    $message = 'QTY tidak ditemukan / Semua QTY telah di proses.';
                }
            }else{
                $success = false;
                $message = 'Item tidak ditemukan di SPK Detail.';
            }
        }
        else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteProses()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $noSpk = $request->post('no_spk');
            $urutan = $request->post('urutan');
            $itemCode = $request->post('item_code');
            $prosesSpk = $this->findModel($noSpk);
            if($prosesSpk->status_produksi == 2){
                $model = SpkDetailProses::findOne(['no_spk'=>$noSpk, 'urutan'=>$urutan, 'item_code'=>$itemCode]);
                if($model->delete()){
                    foreach($model->datas as $index=>$val){
                        $val->urutan = $index +1;
                        if(!$val->save()){
                            $success = false;
                            foreach($val->errors as $error => $value){
                                $message .= strtoupper($value[0].', ');
                            }
                            $message = substr($message, 0, -2);
                        }
                    }

                    $message = '['.$model->no_spk.'-'.$model->urutan.'] SUCCESS DELETE SPK PROSES.';
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE SPK PROSES: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }
            }else{
                $success = false;
                $message = 'SPK SUDAH DI PROSES. TIDAK BISA MENGHAPUS PROSES.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }
}