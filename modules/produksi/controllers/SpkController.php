<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterProses;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkSearch;
use app\modules\produksi\models\SpkProduksi;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderProses;
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
                                'index', 'view', 'update', 'delete-proses', 'list-mesin', 'list-uk_kertas', 'print-preview',
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('surat-perintah-kerja')),
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
        return $this->render('view', [
            'model' => $this->findModel($no_spk),
        ]);
    }

    /**
     * Updates an existing Spk model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_spk No Spk
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_spk)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($no_spk);
        $column = $model->setListColumn();
        $spkProduksi = new SpkProduksi();
        if($this->request->isPost){
            if($spkProduksi->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $spkProduksi->attributes = $spkProduksi->attributes;
                    // KONVERSI STOCK ORDER
                    $stock = 0;
                    $soItem = $model->itemMaterial;
                    $stockItem = $soItem->inventoryStock;
                    if(isset($stockItem)){
                        $stock = $stockItem->satuanTerkecil($soItem->item_code, [
                            0=>$soItem->qty_order_1,
                            1=>$soItem->qty_order_2
                        ]);
                    }
                    if(!empty($model->up_produksi) || $model->up_produksi != 0){
                        $stock += $stock * ($model->up_produksi/100);
                    }
                    // END KONVERSI STOCK ORDER
                    
                    $m_proses = $spkProduksi->proses;
                    if(strpos($m_proses->name, 'Potong') !== false){
                        $spkProduksi->uk_potong = $soItem->item->panjang.'x'.$soItem->item->lebar;
                    }else{
                        $soPotong = $model->itemPotong($model->no_so, $spkProduksi->potong_id);
                        $spkProduksi->uk_potong = $soPotong->panjang.'x'.$soPotong->lebar;
                    }

                    $qty_proses = $spkProduksi->qty_proses = str_replace(',', '', $spkProduksi->qty_proses);
                    if($stock >= $qty_proses){
                        $totalQTY = $spkProduksi->qtyProses + $qty_proses;
                        $sisaProses = $stock - $spkProduksi->qtyProses;
                        if($stock >= $totalQTY){
                            $spkProduksi->gram = $soItem->item->gram;
                            $spkProduksi->proses_type = $m_proses->type;
                            $spkProduksi->mesin_type = $m_proses->mesin_type;
                            $spkProduksi->urutan = $spkProduksi->count +1;
                            $spkProduksi->status_produksi =1;
                            if(!$spkProduksi->save()){
                                $success = false;
                                $message = (count($spkProduksi->errors) > 0) ? 'ERROR CREATE SPK PRODUKSI: ' : '';
                                foreach($spkProduksi->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'Sisa Qty yang belum di proses '.$sisaProses;
                        }
                    }else{
                        $success = false;
                        $message = 'Qty tidak boleh lebih besar dari Order.';
                    }

                    if($success){
                        $message = '['.$model->no_spk.'] SUCCESS CREATE SPK.';
                        $transaction->commit();
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['update', 'no_spk' => $model->no_spk]);
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
        }

        return $this->render('update', [
            'model' => $model,
            'spkProduksi' => $spkProduksi,
            'operator' => $column['operator'],
            'so_proses' => $column['so_proses'],
        ]);
    }

    public function actionDeleteProses($no_spk, $item_code, $urutan, $potong_id)
    {
        $success = true;
        $message = '';
        $model = SpkProduksi::findOne(['no_spk'=>$no_spk, 'item_code'=>$item_code, 'urutan'=>$urutan, 'potong_id'=>$potong_id]);
        if($model->status_produksi == 1){
            if($model->delete()){
                foreach($model->alls as $index=>$val){
                    $val->urutan = $index +1;
                    if(!$val->save()){
                        $success = false;
                        foreach($val->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }
                $message = 'DELETE DETAIL PROSES PRODUKSI SUCCESSFULLY';
            }else{
                $success = false;
                foreach($model->errors as $error => $value){
                    $message = $value[0].', ';
                }
                $message = substr($message, 0, -2);
            }
        }else{
            $success = false;
            $message = 'Data ini tidak bisa dihapus. Proses sedang / sudah berjalan.';
        }
        \Yii::$app->session->setFlash(($success) ? 'success' : 'danger', $message);
        return $this->redirect(['update', 'no_spk' => $model->no_spk]);
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

    public function actionListMesin($code)
    {
        $m_proses = MasterProses::findOne(['code'=>$code]);
        if(isset($m_proses)){
            $model = MasterMesin::find()
                ->select(['code', 'name'])
                ->where(['type_code'=>$m_proses->mesin_type, 'status'=>1])
                ->asArray()
                ->all();
        }
        return json_encode($model);
    }

    public function actionListUk_kertas($code, $no_spk)
    {
        $model = $this->findModel($no_spk);
        if(isset($model)){
            $m_proses = MasterProses::findOne(['code'=>$code]);
            $data = [];
            if(strpos($m_proses->name, 'Potong') !== false){
                $soItem = $model->itemMaterial;
                $data[0]['name'] = $soItem->item->panjang.'x'.$soItem->item->lebar;
                $data[0]['potong_id'] = 9;
            }else{
                $data = SalesOrderPotong::find()
                    ->select(['concat(panjang, "x", lebar) as name', 'urutan as potong_id'])
                    ->asArray()
                    ->all();
            }
        }
        return json_encode($data);
    }

    public function actionPrintPreview($no_spk, $item_code, $urutan, $potong_id)
    {
        $model = $this->findModel($no_spk);
        $spkProduksi = SpkProduksi::findOne(['no_spk'=>$no_spk, 'item_code'=>$item_code, 'urutan'=>$urutan, 'potong_id'=>$potong_id]);
        $m_proses = MasterProses::findOne(['code'=>$spkProduksi->proses_code]);
        $header = '';
        $type = '';
        if(strpos($m_proses->name, 'Cetak') !== false){
            $header = 'SPK. CETAK';
            $type = 'cetak';
        }
        if(strpos($m_proses->name, 'Potong') !== false){
            $header = 'SPK. POTONG';
            $type = 'potong';
        }
        if(strpos($m_proses->name, 'Pond') !== false){
            $header = 'SPK. POND';
            $type = 'pond';
        }
        $data = $this->renderPartial('_preview', [
            'model'=>$model,
            'header'=>$header,
            'spkProduksi'=>$spkProduksi,
            'type'=>$type
        ]);
        return json_encode(['data'=>$data]);
    }
}
