<?php

namespace app\modules\produksi\controllers;

use app\models\DataList;
use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterMesin;
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
                            'actions' => [
                                'index', 'view', 'layar', 'create', 'update', 'print', 'post', 'get-data', 'data-detail', 'popup-input',
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('proses-produksi-sales-order')),
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
        $success = true;
        $message = '';
        $dataList = DataList::setListColumn();
        $model = $this->findModel($no_spk);
        $spkHistory = new SpkOrderHistory();
        if($this->request->isPost){
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
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

        return $this->render('layar', [
            'model' => $model,
            'dataList' => $dataList,
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
        $model = SpkOrderProses::findOne($no_spk, $item_code, $proses_id);
        if ($model !== null)
            return $model;
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findHistory($no_spk, $item_code, $proses_id, $urutan)
    {
        $model = SpkOrderHistory::findOne($no_spk, $item_code, $proses_id, $urutan);
        if ($model !== null)
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
                if(empty($data['tgl_spk'])){
                    $success = false;
                    $message = 'Tgl SPK wajib diisi.';
                }
                
                if(empty($data['outsource_code'])){
                    if(empty($data['mesin_code'])){
                        $success = false;
                        $message = 'Nama Mesin wajib diisi.';
                    }
                    else if(empty($data['user_id'])){
                        $success = false;
                        $message = 'Operator mesin wajib diisi.';
                    }
                }else{
                    if(empty($data['nopol'])){
                        $success = false;
                        $message = 'No. Polisi wajib diisi.';
                    }
                    else if(empty($data['no_sj'])){
                        $success = false;
                        $message = 'No SJ wajib diisi.';
                    }
                }
                
                if($success){
                    $spkProses = SpkOrderProses::find()
                        ->where(['no_spk'=>$data['no_spk'], 'item_code'=>$data['item_code'], 'proses_id'=>$data['proses_id']])
                        ->one();
                    if(isset($spkProses)){
                        $qty = str_replace(',', '', $data['qty_proses']);
                        if($qty <= $spkProses->sisa){
                            $spkProses->status_produksi = 1;
                            if($spkProses->save()){
                                $spkHistory = new SpkOrderHistory();
                                $spkHistory->attributes = $spkProses->attributes;
                                $spkHistory->attributes = (array)$data;
                                $spkHistory->urutan = $spkHistory->count +1;
                                $spkHistory->status_produksi = 1;
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
                            $message = 'QTY Proses tidak boleh melebihi sisa QTY. Sisa QTY = '.$spkProses->sisa;
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

    public function actionGetData($no_spk, $item_code, $proses_id, $mesin_type)
    {
        $success = true;
        $message = '';
        $model = $this->findProses($no_spk, $item_code, $proses_id);
        $total = 0;
        foreach($model->historys as $val){
            if($val->status_produksi == 1) $total += $val->qty_proses;
            if($val->status_produksi == 3) $total += $val->qty_hasil;
        }
        $sisa = $model->qty_proses - $total;
        $mesin = [];
        if($sisa > 0){
            $model = [
                'no_spk' => $no_spk, 'item_code' => $item_code, 'proses_id' => $proses_id,
                'tgl_spk' => date('d-m-Y'), 'qty_proses' => $model->qty_proses - $total,
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
        $dataProses = SpkOrderProses::find()->all();
        $historyNotOutsource = SpkOrderHistory::find()
            ->where('outsource_code is null OR outsource_code=""')
            ->all();
        $historyWithOutsource = SpkOrderHistory::find()
            ->where('outsource_code is not null OR outsource_code <> ""')
            ->all();
        $data = $this->renderAjax('_detail', [
            'model' => $model,
            'dataProses' => $dataProses,
            'historyNotOutsource' => $historyNotOutsource,
            'historyWithOutsource' => $historyWithOutsource
        ]);
        return json_encode(['data'=>$data]);
    }
    /** /INPUT PROSES */

    /** INPUT HASIL */
    public function actionPopupInput($no_spk, $item_code, $proses_id, $urutan)
    {
        $model = $this->findHistory($no_spk, $item_code, $proses_id, $urutan);
        return $this->renderAjax('_popup_hasil', [
            'model' => $model
        ]);
    }

    public function actionUpdate()
    {
        $request = \Yii::$app->request;
        $success = true;
		$message = 'INPUT HASIL PRODUKSI SUCCESSFULLY';
        if($request->isPost){
            try{
                $data = $request->post('SpkOrderHistory');
                if($data['qty_proses'] >= $data['qty_hasil']){
                    $model = $this->findHistory($no_spk, $item_code, $proses_id, $urutan);
                }else{
                    $success = false;
                    $message = 'Qty hasil tidak boleh lebih besar dari Qty yang diproses.';
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
                                $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI: ' : '';
                                foreach($model->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                        foreach($model->historyOnStarts as $val){
                            $val->status_produksi = 2;
                            if(!$val->save()){
                                $success = false;
                                $message = (count($model->errors) > 0) ? 'ERROR UPDATE STATUS PRODUKSI: ' : '';
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
        return $this->redirect(['layar', 'no_spk' => $model->no_spk]);
    }
}