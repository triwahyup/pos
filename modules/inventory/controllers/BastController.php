<?php

namespace app\modules\inventory\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryBast;
use app\modules\inventory\models\InventoryBastDetail;
use app\modules\inventory\models\InventoryBastSearch;
use app\modules\inventory\models\InventoryStockBarang;
use app\modules\inventory\models\InventoryStockBast;
use app\modules\inventory\models\TempInventoryBastDetail;
use app\modules\master\models\MasterKode;
use app\modules\master\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * BastController implements the CRUD actions for InventoryBast model.
 */
class BastController extends Controller
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
                                'index', 'view', 'create', 'update', 'delete', 'post',
                                'list-barang', 'search', 'autocomplete', 'barang', 'popup',
                                'create-temp', 'update-temp', 'delete-temp', 'temp', 'get-temp',
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('bast-barang')),
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
     * Lists all InventoryBast models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventoryBastSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InventoryBast model.
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
     * Creates a new InventoryBast model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $profile = Profile::find()
            ->select(['profile.name'])
            ->leftJoin('master_kode b', 'b.code = profile.typeuser_code')
            ->where(['profile.status' => 1])
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_SUP'].'"')
            ->indexBy('user_id')
            ->column();
        $type = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_BAST'], 'status'=>1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = new InventoryBast();
        $temp = new TempInventoryBastDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->code = $model->generateCode();
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $detail = new InventoryBastDetail();
                                $detail->attributes = $temp->attributes;
                                $detail->code = $model->code;
                                if(!$detail->save()){
                                    $success = false;
                                    $message = (count($detail->errors) > 0) ? 'ERROR CREATE BAST DETAIL: ' : '';
                                    foreach($detail->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE BAST: DETAIL IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE BAST: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS CREATE BAST.';
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
            'profile' => $profile,
            'temp' => $temp,
            'type' => $type,
        ]);
    }

    /**
     * Updates an existing InventoryBast model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $profile = Profile::find()
            ->select(['profile.name'])
            ->leftJoin('master_kode b', 'b.code = profile.typeuser_code')
            ->where(['profile.status' => 1])
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_SUP'].'"')
            ->indexBy('user_id')
            ->column();
        $type = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_BAST'], 'status'=>1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = $this->findModel($code);
        $temp = new TempInventoryBastDetail();
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if($model->save()){
                    if(count($model->temps) > 0){
                        foreach($model->details as $empty)
                            $empty->delete();
                        foreach($model->temps as $temp){
                            $detail = new InventoryBastDetail();
                            $detail->attributes = $temp->attributes;
                            if(!$detail->save()){
                                $success = false;
                                $message = (count($detail->errors) > 0) ? 'ERROR UPDATE BAST DETAIL: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= strtoupper($value[0].', ');
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = 'ERROR UPDATE BAST: DETAIL IS EMPTY.';
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE BAST: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $this->emptyTemp();
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS UPDATE BAST.';
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
                \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Post ke Stock BAST.');
                return $this->redirect(['index']);
            }else{
                $this->emptyTemp();
                foreach($model->details as $detail){
                    $temp = new TempInventoryBastDetail();
                    $temp->attributes = $detail->attributes;
                    $temp->user_id = \Yii::$app->user->id;
                    if(!$temp->save()){
                        $message = (count($temp->errors) > 0) ? 'ERROR LOAD BAST DETAIL: ' : '';
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
            'profile' => $profile,
            'temp' => $temp,
            'type' => $type,
        ]);
    }

    /**
     * Deletes an existing InventoryBast model.
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
                \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Post ke Stock BAST.');
            }else{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->status = 0;
                    if($model->save()){
                        foreach($model->details as $detail){
                            $detail->status = 0;
                            if(!$detail->save()){
                                $success = false;
                                $message = (count($detail->errors) > 0) ? 'ERROR DELETE DETAIL BAST: ' : '';
                                foreach($detail->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR DELETE BAST: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
    
                    if($success){
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS DELETE BAST.';
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
     * Finds the InventoryBast model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return InventoryBast the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = InventoryBast::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListBarang()
    {
        $model = InventoryStockBarang::find()
            ->where(['status'=>1])
            ->orderBy(['barang_code'=>SORT_ASC])
            ->limit(10)
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_barang', ['model'=>$model])]);
    }

    public function actionAutocomplete()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = InventoryStockBarang::find()
                ->alias('a')
                ->select(['b.code', 'concat(b.code,"-",b.name) label', 'concat(b.code,"-",b.name) name'])
                ->leftJoin('master_barang b', 'b.code = barang_code')
                ->where(['b.status'=>1])
                ->andWhere('concat(b.code,"-",b.name) LIKE "%'.$_POST['search'].'%"')
                ->orderBy(['b.code'=>SORT_ASC])
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
            $model = InventoryStockBarang::find()
                ->where(['barang_code'=>$_POST['code'], 'status'=>1])
                ->orderBy(['barang_code'=>SORT_ASC])
                ->limit(10)
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_barang', ['model'=>$model])]);
    }

    public function actionBarang()
    {
        $model = InventoryStockBarang::find()
            ->alias('a')
            ->select(['a.*', 'b.name as name', 'b.satuan_code', 'c.name as um'])
            ->leftJoin('master_barang b', 'b.code = a.barang_code')
            ->leftJoin('master_satuan c', 'c.code = b.satuan_code')
            ->where(['b.code'=>$_POST['code'], 'a.status'=>1])
            ->asArray()
            ->one();
        if(empty($model)){
            $model = [];
        }
        return json_encode($model);
    }

    public function actionTemp()
    {
        $temps = TempInventoryBastDetail::findAll(['user_id'=> \Yii::$app->user->id]);
        $model =  $this->renderAjax('_temp', ['temps'=>$temps]);
        return json_encode(['model'=>$model]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempInventoryBastDetail::find()->where(['id'=>$id])->asArray()->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE TEMP SUCCESSFULLY';
        if($request->isPost){
            $temp = new TempInventoryBastDetail();
            $dataHeader = $request->post('InventoryBast');
            $dataTemp = $request->post('TempInventoryBastDetail');
            $temp->attributes = (array)$dataTemp;
            if(!empty($dataTemp['name'])){
                if($dataTemp['qty'] > 0){
                    $temp->code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
                    $temp->urutan = $temp->count +1;
                    $temp->user_id = \Yii::$app->user->id;
                    if(!$temp->save()){
                        $success = false;
                        foreach($temp->errors as $error => $value){
                            $message = $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = 'Qty wajib diisi.';
                }
            }else{
                $success = false;
                $message = 'Barang wajib diisi.';
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
            $dataHeader = $request->post('InventoryBast');
            $dataTemp = $request->post('TempInventoryBastDetail');
            $temp = $this->findTemp($dataTemp['id']);
            $temp->attributes = (array)$dataTemp;
            if(!empty($dataTemp['name'])){
                if($dataTemp['qty'] > 0){
                    $temp->code = (!empty($dataHeader['code'])) ? $dataHeader['code'] : 'tmp';
                    if(!$temp->save()){
                        $success = false;
                        foreach($temp->errors as $error => $value){
                            $message = $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = 'Qty wajib diisi.';
                }
            }else{
                $success = false;
                $message = 'Barang wajib diisi.';
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
        $temp = TempInventoryBastDetail::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempInventoryBastDetail::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempInventoryBastDetail::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_inventory_bast_detail AUTO_INCREMENT=1')->query();
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
                $model->post=1;
                if($model->save()){
                    foreach($model->details as $val){
                        $stockBarang = InventoryStockBarang::findOne(['barang_code'=>$val->barang_code, 'supplier_code'=>$val->supplier_code, 'status'=>1]);
                        if(isset($stockBarang)){
                            if($stockBarang->stock > $val->qty){
                                $stockBarang->stock = $stockBarang->stock-$val->qty;
                                if(!$stockBarang->save()){
                                    $success = false;
                                    $message = (count($stockBarang->errors) > 0) ? 'ERROR UPDATE STOCK ITEM: ' : '';
                                    foreach($stockBarang->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }

                                $stockBast = new InventoryStockBast();
                                $stockBast->attributes = $val->attributes;
                                $stockBast->no_document = $model->code;
                                $stockBast->tgl_document = $model->date;
                                $stockBast->type_document = "BAST";
                                $stockBast->status_document = "OUT";
                                $stockBast->qty_out = $val->qty;
                                $stockBast->stock = $stockBarang->stock;
                                if(!$stockBast->save()){
                                    $success = false;
                                    $message = (count($stockBast->errors) > 0) ? 'ERROR UPDATE STOCK TRANSACTION: ' : '';
                                    foreach($stockBast->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }else{
                                $success = false;
                                $message = 'SISA STOCK BARANG '.$val->barang_code.' TIDAK MENCUKUPI. SISA '.$stockBarang->stock;
                            }
                        }else{
                            $success = false;
                            $message = 'STOCK BARANG '.$val->BARANG.' TIDAK DITEMUKAN.';
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR POST BAST TO STOCK BAST: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $message = '['.$model->code.'] SUCCESS POST BAST TO STOCK BAST.';
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
            $message = 'Data Bast not valid.';
        }
        if(!$success){
            \Yii::$app->session->setFlash('error', $message);
        }
        return $this->redirect(['view', 'code' => $model->code]);
    }
}
