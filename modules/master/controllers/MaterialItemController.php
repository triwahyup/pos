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
use app\modules\master\models\MasterMaterialItemSearch;
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
                            'actions' => ['create'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'generate-code', 'um'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('material-item')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
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
        $model = new MasterMaterialItem();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    if($model->save()){
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
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE ITEM: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
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
        }

        return $this->render('create', [
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
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE ITEM: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
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

        return $this->render('update', [
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
}
