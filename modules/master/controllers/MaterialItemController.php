<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterGroupMaterial;
use app\modules\master\models\MasterGroupSupplier;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterSatuan;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMaterialItemPricelist;
use app\modules\master\models\MasterMaterialItemSearch;
use app\modules\master\models\TempMasterMaterialItemPricelist;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * MaterialItemController implements the CRUD actions for MasterMaterialItem model.
 */
class MaterialItemController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'generate-code', 'um', 'get-temp', 'temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update', 'update-temp', 'status-active'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete', 'delete-temp'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
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
     * Lists all MasterMaterialItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterMaterialItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterMaterialItem model.
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
     * Creates a new MasterMaterialItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $type = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_MATERIAL'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $material = [];
        $satuan = [];
        $groupMaterial = [];
        $groupSupplier = [];

        $success = true;
        $message = '';
        $temp = new TempMasterMaterialItemPricelist();
        $model = new MasterMaterialItem();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    if($model->save()){
                        if(count($model->temps()) > 0){
                            foreach($model->temps() as $temp){
                                $pricelist = new MasterMaterialItemPricelist();
                                $pricelist->attributes = $temp->attributes;
                                if(!$pricelist->save()){
                                    $success = false;
                                    $message = (count($pricelist->errors) > 0) ? 'ERROR CREATE PRICELIST: ' : '';
                                    foreach($pricelist->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }

                            $stockItem = new InventoryStockItem();
                            $stockItem->item_code = $model->code;
                            if(!$stockItem->save()){
                                $success = false;
                                $message = (count($stockItem->errors) > 0) ? 'ERROR CREATE INVENTORY STOCK: ' : '';
                                foreach($stockItem->errors as $error => $value){
                                    $message .= $value[0].', ';
                                }
                                $message = substr($message, 0, -2);
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR CREATE PRICELIST: PRICELIST IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE ITEM: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS CREATE ITEM.';
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
            'temp' => $temp,
            'model' => $model,
            'type' => $type,
            'material' => $material,
            'satuan' => $satuan,
            'groupMaterial' => $groupMaterial,
            'groupSupplier' => $groupSupplier,
        ]);
    }

    /**
     * Updates an existing MasterMaterialItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $success = true;
        $message = '';
        $temp = new TempMasterMaterialItemPricelist();
        $model = $this->findModel($code);
        $type = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_MATERIAL'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $material = MasterMaterial::find()
            ->select(['name'])
            ->where(['type_code'=>$model->type_code, 'status'=>1])
            ->indexBy('code')
            ->column();
        $satuan= MasterSatuan::find()
            ->select(['name'])
            ->where(['type_code'=>$model->type_code, 'status'=>1])
            ->indexBy('code')
            ->column();
        $groupMaterial = [];
        $groupSupplier = [];
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    if($model->save()){
                        if(count($model->temps) > 0){
                            foreach($model->pricelists as $empty)
                                $empty->delete();
                            foreach($model->temps as $temp){
                                $pricelist = new MasterMaterialItemPricelist();
                                $pricelist->attributes = $temp->attributes;
                                if(!$pricelist->save()){
                                    $success = false;
                                    $message = (count($pricelist->errors) > 0) ? 'ERROR CREATE PRICELIST: ' : '';
                                    foreach($pricelist->errors as $error => $value){
                                        $message .= strtoupper($value[0].', ');
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }else{
                            $success = false;
                            $message = 'ERROR UPDATE PRICELIST: PRICELIST IS EMPTY.';
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE ITEM: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $this->emptyTemp();
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS UPDATE ITEM.';
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
        }else{
            $this->emptyTemp();
            foreach($model->pricelists as $pricelist){
                $temp = new TempMasterMaterialItemPricelist();
                $temp->attributes = $pricelist->attributes;
                $temp->user_id = \Yii::$app->user->id;
                if(!$temp->save()){
                    $message = (count($temp->errors) > 0) ? 'ERROR LOAD DATA PRICELIST: ' : '';
                    foreach($temp->errors as $error => $value){
                        $message .= strtoupper($value[0].', ');
                    }
                    $message = substr($message, 0, -2);
                    \Yii::$app->session->setFlash('error', $message);
                }
            }
        }

        return $this->render('update', [
            'temp' => $temp,
            'model' => $model,
            'type' => $type,
            'material' => $material,
            'satuan' => $satuan,
            'groupMaterial' => $groupMaterial,
            'groupSupplier' => $groupSupplier,
        ]);
    }

    /**
     * Deletes an existing MasterMaterialItem model.
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
            $connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
            try{
                $model->status = 0;
                if($model->save()){
                    $stockItem = InventoryStockItem::findOne(['item_code'=>$code, 'status' => 1]);
                    if(isset($stockItem)){
                        $stockItem->status = 0;
                        if(!$stockItem->save()){
                            $success = false;
                            $message = (count($stockItem->errors) > 0) ? 'ERROR DELETE INVENTORY STOCK: ' : '';
                            foreach($stockItem->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }else{
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE ITEM: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS DELETE ITEM.';
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
     * Finds the MasterMaterialItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterMaterialItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterMaterialItem::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGenerateCode($type)
    {
        $model = [];
        if(isset($type)){
            $model['code'] = MasterMaterialItem::generateCode($type);
        }

        $model['material'] = MasterMaterial::find()
            ->select(['code', 'name'])
            ->where(['type_code'=>$type, 'status'=>1])
            ->asArray()
            ->all();
        $model['satuan'] = MasterSatuan::find()
            ->select(['code', 'name'])
            ->where(['type_code'=>$type, 'status'=>1])
            ->asArray()
            ->all();
        return json_encode($model);
    }

    public function actionUm($code)
    {
        $model = MasterSatuan::find()
            ->where(['code'=>$code, 'status'=>1])
            ->asArray()
            ->one();
        return json_encode($model);
    }

    public function actionTemp()
    {
        $temps = TempMasterMaterialItemPricelist::findAll(['user_id'=> \Yii::$app->user->id]);
        return json_encode(['model'=>$this->renderAjax('_temp', ['temps'=>$temps])]);
    }

    public function actionGetTemp($id)
    {
        $temp = TempMasterMaterialItemPricelist::find()->where(['id'=>$id])->asArray()->one();
        return json_encode($temp);
    }

    public function actionCreateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $model = $request->post('MasterMaterialItem');
            if(isset($model)){
                $data = $request->post('TempMasterMaterialItemPricelist');
                $temp = new TempMasterMaterialItemPricelist();
                $temp->attributes = (array)$data;
                $temp->item_code = $model['code'];
                $temp->urutan = $temp->count +1;
                $temp->user_id = \Yii::$app->user->id;
                if($temp->save()){
                    $message = 'CREATE TEMP SUCCESSFULLY';
                }else{
                    $success = false;
                    foreach($temp->errors as $error => $value){
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

    public function actionUpdateTemp()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('TempMasterMaterialItemPricelist');
            $temp = $this->findTemp($data['id']);
            $temp->attributes = (array)$data;
            if($temp->save()){
                $message = 'UPDATE TEMP SUCCESSFULLY';
            }else{
                $success = false;
                foreach($temp->errors as $error => $value){
                    $message = $value[0].', ';
                }
                $message = substr($message, 0, -2);
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    public function actionDeleteTemp($id)
    {
        $success = true;
        $message = '';
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
                $message = 'DELETE TEMP SUCCESSFULLY';
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

    public function actionStatusActive($id)
    {
        $temp = $this->findTemp($id);
        if(isset($temp)){
            foreach($temp->tmps as $index=>$val){
                $val->status_active=0;
                $val->save();
            }
            $temp->status_active=1;
            $temp->save();
        }
    }

    protected function findTemp($id)
    {
        $temp = TempMasterMaterialItemPricelist::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function emptyTemp()
    {
        TempMasterMaterialItemPricelist::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
        $temp = TempMasterMaterialItemPricelist::find()->all();
        if(empty($temp)){
            $connection = \Yii::$app->db;
			$connection->createCommand('ALTER TABLE temp_master_material_item_pricelist AUTO_INCREMENT=1')->query();
        }
    }
}
