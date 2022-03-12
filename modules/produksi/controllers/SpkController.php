<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterProses;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkSearch;
use app\modules\produksi\models\SpkDetail;
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
                                'index', 'view', 'update', 'list-mesin', 'list-uk_kertas', 'print-preview',
                                'get-proses', 'change-proses', 'delete-proses', 'cancel-proses', 'post-proses',
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
        $spkDetail = new SpkDetail();
        if($this->request->isPost){
            if($spkDetail->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $spkDetail->attributes = $spkDetail->attributes;
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
                    
                    $m_proses = $spkDetail->proses;
                    if(strpos($m_proses->name, 'Potong') !== false){
                        $spkDetail->uk_potong = $soItem->item->panjang.'x'.$soItem->item->lebar;
                    }else{
                        $soPotong = $model->itemPotong($model->no_so, $spkDetail->potong_id);
                        $spkDetail->uk_potong = $soPotong->panjang.'x'.$soPotong->lebar;
                    }

                    $qty_proses = str_replace(',', '', $spkDetail->qty_proses);
                    if($stock >= $qty_proses){
                        $totalQTY = $spkDetail->qtyProses + $qty_proses;
                        $sisaProses = $stock - $spkDetail->qtyProses;
                        if($stock >= $totalQTY){
                            if(!empty($spkDetail->outsource_code)){
                                $spkDetail->mesin_code = '000';
                                $spkDetail->user_id = '0';
                            }
                            $spkDetail->gram = $soItem->item->gram;
                            $spkDetail->proses_type = $m_proses->type;
                            $spkDetail->mesin_type = $m_proses->mesin_type;
                            $spkDetail->urutan = $spkDetail->count +1;
                            $spkDetail->status_produksi =1;
                            if(!$spkDetail->save()){
                                $success = false;
                                $message = (count($spkDetail->errors) > 0) ? 'ERROR CREATE SPK PRODUKSI: ' : '';
                                foreach($spkDetail->errors as $error => $value){
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
            'spkDetail' => $spkDetail,
            'outsource' => $column['outsource'],
            'operator' => $column['operator'],
            'so_proses' => $column['so_proses'],
        ]);
    }

    public function actionGetProses($no_spk, $item_code, $urutan, $potong_id)
    {
        $success = true;
        $message = '';
        $model = SpkDetail::find()
            ->where(['no_spk'=>$no_spk, 'item_code'=>$item_code, 'urutan'=>$urutan, 'potong_id'=>$potong_id])
            ->asArray()
            ->one();
        if($model['status_produksi'] == 3 || $model['status_produksi'] == 5){
            $success = false;
            $message = 'Tidak dapat melakukan perubahan pada data ini karena data ini sudah selesai di proses.';
        }
        if($model['status_produksi'] == 4){
            $success = false;
            $message = 'Update proses bisa dilakukan ketika status produksi '.\Yii::$app->params['IN_PROGRESS'];
        }
        return json_encode(['success'=>$success, 'message'=>$message, 'model'=>$model]);
    }

    public function actionChangeProses()
    {
        $success = true;
        $message = '';
        $request = \Yii::$app->request;
        if($request->isPost){
            $data = $request->post('SpkDetail');
            $model = $this->findProduksi($data['no_spk'], $data['item_code'], $data['urutan'], $data['potong_id']);
            $model->attributes = (array)$data;
            $qty_hasil = str_replace(',', '', $model->qty_hasil);
            if(!empty($qty_hasil)){
                if($qty_hasil <= $model->qty_proses){
                    if($model->qty_rusak !=0 || $model->qty_rusak != null){
                        $model->status_produksi =  5; // RUSAK SEBAGIAN;
                    }else{
                        if($model->qty_hasil < $model->qty_proses){
                            $model->status_produksi = 4; // DONE SEBAGIAN
                        }else{
                            $model->status_produksi = 3; // DONE
                        }
                    }
                }else{
                    $success = false;
                    $message = 'Qty hasil tidak boleh lebih dari qty proses.';
                }
            }

            if($model->save()){
                $message = '['.$model->no_spk.'] SUCCESS UPDATE SPK.';
            }else{
                $success = false;
                $message = (count($model->errors) > 0) ? 'ERROR UPDATE SPK PRODUKSI: ' : '';
                foreach($model->errors as $error => $value){
                    $message .= strtoupper($value[0].', ');
                }
                $message = substr($message, 0, -2);
            }
        }
        \Yii::$app->session->setFlash(($success) ? 'success' : 'danger', $message);
        return $this->redirect(['update', 'no_spk' => $model->no_spk]);
    }

    public function actionCancelProses($no_spk)
    {
        return $this->redirect(['update', 'no_spk' => $no_spk]);
    }

    public function actionDeleteProses($no_spk, $item_code, $urutan, $potong_id)
    {
        $success = true;
        $message = '';
        $model = $this->findProduksi($no_spk, $item_code, $urutan, $potong_id);
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
            if($model->status_produksi == 2){
                $message = 'Data ini tidak bisa dihapus, proses produksi sedang berjalan.';
            }else if($model->status_produksi == 3 || $model->status_produksi == 4){
                $message = 'Data ini tidak bisa dihapus, data sudah selesai di proses.';
            }
        }
        \Yii::$app->session->setFlash(($success) ? 'success' : 'danger', $message);
        return $this->redirect(['update', 'no_spk' => $model->no_spk]);
    }

    public function actionPostProses($no_spk, $type)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_spk);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if($type == \Yii::$app->params['ON_START']){
                    $model->status_produksi=3;
                    foreach($model->produksiInProgress as $val){
                        if(empty($val->qty_hasil) || $val->qty_hasil == null){
                            $success = false;
                            $message = 'Qty hasil masih ada yang belum di input.';
                        }
                    }
                }else if($type == \Yii::$app->params['IN_PROGRESS']){
                    $model->status_produksi=2;
                    if(!count($model->produksiOnStarts) > 0){
                        $success = false;
                        $message = 'Data yang akan di proses produksi masih kosong.';
                    }
                }

                if($model->save()){
                    if($type == \Yii::$app->params['IN_PROGRESS']){
                        foreach($model->produksiOnStarts as $val){
                            $val->status_produksi = 2;
                            if(!$val->save()){
                                $success = false;
                                $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI SPK_PRODUKSI: ' : '';
                                foreach($model->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI SPK: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->no_spk.'] SUCCESS UPDATE STATUS PRODUKSI SPK.';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'no_spk' => $model->no_spk]);
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
            $message = 'Data SPK not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
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

    protected function findProduksi($no_spk, $item_code, $urutan, $potong_id)
    {
        $model = SpkDetail::findOne(['no_spk'=>$no_spk, 'item_code'=>$item_code, 'urutan'=>$urutan, 'potong_id'=>$potong_id]);
        if($model !=null){
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
                    ->where(['code'=>$model->no_so])
                    ->asArray()
                    ->all();
            }
        }
        return json_encode($data);
    }

    public function actionPrintPreview($no_spk, $item_code, $urutan, $potong_id)
    {
        $model = $this->findModel($no_spk);
        $spkDetail = $this->findProduksi($no_spk, $item_code, $urutan, $potong_id);
        $m_proses = MasterProses::findOne(['code'=>$spkDetail->proses_code]);
        $header = '';
        $type = '';
        if(strpos($m_proses->name, 'Cetak') !== false){
            $header = 'SPK. CETAK';
            $type = 'cetak';
        }else if(strpos($m_proses->name, 'Potong') !== false){
            $header = 'SPK. POTONG';
            $type = 'potong';
        }else if(strpos($m_proses->name, 'Pond') !== false){
            $header = 'SPK. POND';
            $type = 'pond';
        }else if(strpos($m_proses->name, 'Pretel') !== false){
            $header = 'SPK. Pretel';
            $type = 'pretel';
        }else if(strpos($m_proses->name, 'Lem') !== false){
            $header = 'SPK. LEM';
            $type = 'lem';
        }
        $data = $this->renderPartial('_preview', [
            'model'=>$model,
            'header'=>$header,
            'spkDetail'=>$spkDetail,
            'type'=>$type
        ]);
        return json_encode(['data'=>$data]);
    }
}
