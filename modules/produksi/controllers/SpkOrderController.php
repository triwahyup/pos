<?php

namespace app\modules\produksi\controllers;

use app\models\DataList;
use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterKendaraan;
use app\modules\master\models\MasterProses;
use app\modules\produksi\models\SpkOrder;
use app\modules\produksi\models\SpkOrderSearch;
use app\modules\produksi\models\SpkOrderProses;
use app\modules\produksi\models\SpkOrderHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SpkOrderController implements the CRUD actions for SpkOrder model.
 */
class SpkOrderController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('proses-produksi-sales-order[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'layar', 'print', 'get-data', 'data-detail', 'popup-input', 'list-kendaraan'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('proses-produksi-sales-order[R]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['update', 'post'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('proses-produksi-sales-order[U]')),
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
        $searchModel = new SpkOrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpkOrders model.
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
     * Updates an existing SpkOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_spk No Spk
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionLayar($no_spk)
    {
        $model = $this->findModel($no_spk);
        $spkHistory = new SpkOrderHistory();
        $dataList = DataList::setListColumn();
        return $this->render('layar', [
            'dataList' => $dataList,
            'model' => $model,
            'spkHistory' => $spkHistory,
        ]);
    }

    /**
     * Finds the SpkOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_spk No Spk
     * @return SpkOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_spk)
    {
        if (($model = SpkOrder::findOne($no_spk)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findProses($no_spk, $item_code, $proses_id)
    {
        $model = SpkOrderProses::findOne(['no_spk'=>$no_spk, 'item_code'=>$item_code, 'proses_id'=>$proses_id]);
        if (isset($model))
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findHistory($no_spk, $item_code, $proses_id, $urutan)
    {
        $model = SpkOrderHistory::findOne(['no_spk'=>$no_spk, 'item_code'=>$item_code, 'proses_id'=>$proses_id, 'urutan'=>$urutan]);
        if (isset($model))
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /** INPUT PROSES */
    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE PROSES SPK SUCCESSFULLY';
        if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $data = $request->post('SpkOrderHistory');
                if(empty($data['jenis_pengerjaan'])){
                    $success = false;
                    $message = 'Pengerjaan wajib diisi.';
                }else{
                    if($data['jenis_pengerjaan'] == 0){
                        if(empty($data['mesin_code'])){
                            $success = false;
                            $message = 'Nama Mesin wajib diisi.';
                        }else if(empty($data['user_id'])){
                            $success = false;
                            $message = 'Operator mesin wajib diisi.';
                        }
                    }else{
                        if(empty($data['outsource_code'])){
                            $success = false;
                            $message = 'Outsource wajib diisi.';
                        }else if(empty($data['kendaraan_code'])){
                            $success = false;
                            $message = 'Kendaraan wajib diisi.';
                        }
                    }
                }
                if(empty($data['tgl_spk'])){
                    $success = false;
                    $message = 'Tgl SPK wajib diisi.';
                }

                if($success){
                    $spkProses = SpkOrderProses::find()
                        ->where(['no_spk'=>$data['no_spk'], 'item_code'=>$data['item_code'], 'proses_id'=>$data['proses_id']])
                        ->one();
                    if(isset($spkProses)){
                        $qty = str_replace(',', '', $data['qty_proses']);
                        if($qty <= $spkProses->sisa['sisa']){
                            $model = $this->findModel($data['no_spk']);
                            if($model->status_produksi == 1){
                                $spkProses->status_produksi = 1;
                            }else{
                                $spkProses->status_produksi = 2;
                            }
                            if($spkProses->save()){
                                $spkHistory = new SpkOrderHistory();
                                $spkHistory->attributes = $spkProses->attributes;
                                $spkHistory->attributes = (array)$data;
                                $spkHistory->urutan = $spkHistory->count +1;
                                $spkHistory->qty_hasil = 0;
                                $spkHistory->qty_rusak = 0;
                                $spkHistory->status_produksi = ($model->status_produksi == 1) ? 1: 2;
                                if(!$spkHistory->save()){
                                    $success = false;
                                    foreach($spkHistory->errors as $error => $value){
                                        $message = $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                foreach($spkProses->errors as $error => $value){
                                    $message = $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'QTY Proses tidak boleh melebihi sisa QTY. Sisa QTY = '.$spkProses->sisa['sisa'];
                        }
                    }else{
                        $success = false;
                        $message = 'Data Spk Order Proses tidak ditemukan.';
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

    public function actionListKendaraan()
    {
        $model = MasterKendaraan::find()
            ->alias('a')
            ->select(['a.code as code', 'concat(nopol, " - ", a.name, " - ", b.value) as name'])
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.status' => 1])
            ->asArray()
            ->all();
        return json_encode($model);
    }

    public function actionGetData($no_spk, $item_code, $proses_id, $mesin_type)
    {
        $success = true;
        $message = '';
        $model = $this->findProses($no_spk, $item_code, $proses_id);
        $total = 0;
        foreach($model->historys as $val){
            if($val->status_produksi == 1 || $val->status_produksi == 2 || $val->status_produksi == 4) 
                $total += $val->qty_proses;
            else if($val->status_produksi == 3)
                $total += $val->qty_hasil;
            else if($val->status_produksi == 5)
                $total += $val->qty_hasil + $val->qty_rusak;
        }
        $sisa = $model->qty_proses - $total;
        $mesin = [];
        if($sisa > 0){
            $model = [
                'no_spk' => $no_spk,
                'item_code' => $item_code,
                'proses_id' => $proses_id,
                'no_sj' => $no_spk,
                'tgl_spk' => date('d-m-Y'),
                'qty_proses' => $model->qty_proses - $total,
            ];
            $mesin = MasterMesin::find()
                ->select(['code', 'name'])
                ->where(['type_code'=>$mesin_type, 'status'=>1])
                ->asArray()
                ->all();
        }else{
            $success = false;
            $message = 'Semua QTY telah diproses.';
        }
        return json_encode(['success'=>$success, 'message'=>$message, 'model'=>$model, 'mesin'=>$mesin]);
    }

    public function actionDataDetail($no_spk)
    {
        $model = SpkOrder::findOne(['no_spk'=>$no_spk]);
        $historys = SpkOrderHistory::find()
            ->where(['no_spk'=>$no_spk])
            ->andWhere('outsource_code is null OR outsource_code=""')
            ->orderBy(['no_spk'=>SORT_ASC, 'urutan'=>SORT_ASC])
            ->all();
        $historyNotOutsource = [];
        $historyWithOutsource = [];
        foreach($model->historys as $val){
            if(empty($val->outsource_code) || $val->outsource_code == ""){
                $historyNotOutsource[$val->supplier->name][] = [
                    'attributes' => $val->attributes,
                    'proses_name' => (isset($val->proses)) ? $val->proses->name : '-',
                    'operator_name' => (isset($val->operator)) ? $val->operator->name : '-',
                    'mesin_name' => (isset($val->mesin)) ? $val->mesin->name : '-',
                    'status_produksi' => $val->statusProduksi,
                    'sisa' => $val->sisa,
                ];
            }else{
                $historyWithOutsource[$val->supplier->name][] = [
                    'attributes' => $val->attributes,
                    'proses_name' => (isset($val->proses)) ? $val->proses->name : '-',
                    'outsource_name' => (isset($val->outsource)) ? $val->outsource->name : '',
                    'mesin_name' => (isset($val->mesin)) ? $val->mesin->name : '-',
                    'kendaraan' => (isset($val->kendaraan)) ? $val->kendaraan : '-',
                    'status_produksi' => $val->statusProduksi,
                    'sisa' => $val->sisa,
                ];
            }
        }
        
        $data = $this->renderAjax('_detail', [
            'model' => $model,
            'dataProses' => $model->produksiInAlls,
            'historyNotOutsource' => $historyNotOutsource,
            'historyWithOutsource' => $historyWithOutsource,
        ]);
        return json_encode(['data'=>$data]);
    }
    /** /INPUT PROSES */

    /** INPUT HASIL */
    public function actionPopupInput($no_spk, $item_code, $proses_id, $urutan)
    {
        $model = $this->findHistory($no_spk, $item_code, $proses_id, $urutan);
        $model->qty_proses = number_format($model->qty_proses);
        $model->qty_hasil = (!empty($model->qty_hasil)) ? $model->qty_hasil : $model->qty_proses;
        return $this->renderAjax('_popup_hasil', [
            'model' => $model
        ]);
    }

    public function actionUpdate()
    {
        $request = \Yii::$app->request;
        $success = true;
		if($request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $data = $request->post('SpkOrderHistory');
                $spkHistory = $this->findHistory($data['no_spk'], $data['item_code'], $data['proses_id'], $data['urutan']);
                $spkHistory->attributes = (array)$data;
                $spkHistory->status_produksi = $spkHistory->set_status_produksi;
                $totalResultNRusak = str_replace(',', '', $spkHistory->qty_hasil) + str_replace(',', '', $spkHistory->qty_rusak);
                if($totalResultNRusak <= str_replace(',', '', $spkHistory->qty_proses)){
                    if(!$spkHistory->save()){
                        $success = false;
                        $message = (count($spkHistory->errors) > 0) ? 'ERROR INPUT HASIL PRODUKSI: ' : '';
                        foreach($spkHistory->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }

                    $spkProses = $this->findProses($data['no_spk'], $data['item_code'], $data['proses_id']);
                    $totalHasil = 0;
                    $totalRusak = 0;
                    foreach($spkProses->historys as $val){
                        $totalHasil += $val->qty_hasil;
                        $totalRusak += $val->qty_rusak;
                    }
                    $spkProses->qty_hasil = $totalHasil;
                    $spkProses->qty_rusak = $totalRusak;
                    if(!$spkProses->save()){
                        $success = false;
                        $message = (count($spkProses->errors) > 0) ? 'ERROR UPDATE SPK PROSES: ' : '';
                        foreach($spkProses->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = 'Jumlah QTY tidak boleh lebih besar dari Qty Proses.';
                }

                if($success){
                    $message = '['.$spkHistory->no_spk.'] INPUT HASIL PRODUKSI SUCCESSFULLY';
                    $transaction->commit();
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
                    \Yii::$app->session->setFlash('success', $message);
                }else{
                    $transaction->rollBack();
                }
            }catch(\Exception $e){
                $success = false;
                $message = $e->getMessage();
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }
    /** /INPUT HASIL */

    public function actionPrint($no_spk, $item_code, $proses_id, $urutan)
    {
        $type_proses = [
            'Potong' => ['judul' => 'Spk. Potong', 'type' => 'potong'],
            'Cetak' => ['judul' => 'Spk. Cetak', 'type' => 'cetak'],
            'Water Base' => ['judul' => 'Spk. Water Base', 'type' => 'waterbase'],
            'Laminasi' => ['judul' => 'Spk. Laminasi', 'type' => 'laminasi'],
            'Laminating Glossy' => ['judul' => 'Spk. Laminating Glossy', 'type' => 'laminasi_glossy'],
            'Laminating Doff' => ['judul' => 'Spk. Laminating Doff', 'type' => 'laminasi_doff'],
            'Vernish' => ['judul' => 'Spk. Vernish', 'type' => 'vernish'],
            'UV' => ['judul' => 'Spk. UV', 'type' => 'uv'],
            'Hot Print' => ['judul' => 'Spk. Hot Print', 'type' => 'hot_print'],
            'Embossing' => ['judul' => 'Spk. ', 'type' => 'embossing'],
            'Plong / Pond' => ['judul' => 'Spk. Plong / Pond', 'type' => 'plong'],
            'Pretel' => ['judul' => 'Spk. Pretel', 'type' => 'pretel'],
            'Lem' => ['judul' => 'Spk. Lem', 'type' => 'lem'],
            'Ikat' => ['judul' => 'Spk. Ikat', 'type' => 'ikat'],
            'Bungkus' => ['judul' => 'Spk. Bungkus', 'type' => 'bungkus'],
        ];
        
        $model = $this->findModel($no_spk);
        $spkHistory = $this->findHistory($no_spk, $item_code, $proses_id, $urutan);
        $mp = MasterProses::findOne(['code'=>$spkHistory->proses_code]);
        $type_proses = $type_proses[$mp->name];
        $data = $this->renderPartial('_preview', [
            'model' => $model,
            'spkHistory' => $spkHistory,
            'type_proses' => $type_proses,
        ]);
        return json_encode(['data'=>$data]);
    }

    public function actionPost($no_spk, $type)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_spk);
        if(isset($model)){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                // PROSES AWAL2 PADA SAAT ATUR PROSES
                if($type == \Yii::$app->params['IN_PROGRESS']){
                    $model->status_produksi=2;
                    if(!count($model->produksiOnStarts) > 0){
                        $success = false;
                        $message = 'Data yang akan di proses produksi masih kosong.';
                    }
                }
                // PROSES REVIEW INPUS HASIL PRODUKSI
                else if($type == \Yii::$app->params['IN_REVIEW']){
                    $model->status_produksi=3;
                    if($model->produksiIsNull > 0){
                        $success = false;
                        $message = 'Masih ada proses yang belum dikerjakan. Selesaikan proses terlebih dahulu.';
                    }else if(count($model->historyInProgress) > 0){
                        $success = false;
                        $message = 'Masih ada proses yang belum input hasil produksi.';
                    }else{
                        foreach($model->historys as $val){
                            $sisa = $val->qty_proses - $val->qty_hasil - $val->qty_rusak;
                            if($sisa > 0){
                                $success = false;
                                $message = 'Proses '.$val->proses->name.' urutan ke '.$val->urutan.' masih sisa '.$sisa.' yang belum di input sebagai hasil produksi.';
                            }
                        }
                    }
                }

                if($model->save()){
                    // PROSES AWAL2 PADA SAAT ATUR PROSES
                    if($type == \Yii::$app->params['IN_PROGRESS']){
                        foreach($model->produksiOnStarts as $val){
                            $val->status_produksi = 2;
                            if(!$val->save()){
                                $success = false;
                                $message = (count($val->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI: ' : '';
                                foreach($val->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        foreach($model->historyOnStarts as $val){
                            $val->status_produksi = 2;
                            if(!$val->save()){
                                $success = false;
                                $message = (count($val->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI: ' : '';
                                foreach($val->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }
                    // PROSES REVIEW INPUT HASIL PRODUKSI
                    else if($type == \Yii::$app->params['IN_REVIEW']){
                        foreach($model->produksiInProgress as $val){
                            $val->status_produksi = (empty($val->qty_rusak)) ? 3 : 5;
                            if(!$val->save()){
                                $success = false;
                                $message = (count($val->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI: ' : '';
                                foreach($val->errors as $error => $value){
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
        return $this->redirect(['layar', 'no_spk' => $model->no_spk]);
    }
}