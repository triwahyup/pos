<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockTransaction;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMesin;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkDetail;
use app\modules\produksi\models\SpkDetailBahan;
use app\modules\produksi\models\SpkDetailProduksi;
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
                                'index', 'view',
                                'list-bahan', 'search-bahan', 'item-bahan', 'create-bahan', 'delete-bahan',
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
        $spkBahan = new SpkDetailBahan();
        $spkDetail = SpkDetail::findOne(['no_spk'=>$no_spk]);
        
        return $this->render('view', [
            'model' => $this->findModel($no_spk),
            'spkBahan' => $spkBahan,
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

    public function actionListBahan()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.type_code'=>\Yii::$app->params['MATERIAL_BP_CODE'], 'a.status'=>1])
            ->orderBy(['a.code'=>SORT_ASC])
            ->limit(10)
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_bahan', ['model'=>$model])]);
    }
    
    public function actionSearchBahan()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = MasterMaterialItem::find()
                ->alias('a')
                ->select(['a.*', 'b.composite'])
                ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
                ->leftJoin('master_material c', 'c.code = a.material_code AND c.type_code = a.type_code')
                ->where(['a.type_code'=>\Yii::$app->params['MATERIAL_BP_CODE'], 'a.status'=>1])
                ->andWhere('a.code LIKE "%'.$_POST['search'].'%" 
                    OR a.name LIKE "%'.$_POST['search'].'%" 
                    OR c.name  LIKE "%'.$_POST['search'].'%"')
                ->orderBy(['a.code'=>SORT_ASC])
                ->limit(10)
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
                'c.code as type_bahan_code',
                'c.name as type_bahan',
                'b.composite'])
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
                                $model = new SpkDetailBahan();
                                $model->attributes = (array)$data;
                                $model->attributes = $model->itemBahan->attributes;
                                $model->satuan_bahan_code = $model->itemBahan->satuan_code;
                                $model->urutan = $urutan +1;
                            }
                            
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
}