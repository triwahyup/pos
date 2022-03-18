<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterBarangSearch;
use app\modules\master\models\MasterSatuan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * BarangController implements the CRUD actions for MasterBarang model.
 */
class BarangController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang')),
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
     * Lists all MasterBarang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterBarang model.
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
     * Creates a new MasterBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $satuan= MasterSatuan::find()
            ->select(['master_satuan.name'])
            ->leftJoin('master_kode', 'master_kode.code = master_satuan.type_satuan')
            ->where(['value'=> \Yii::$app->params['TYPE_BARANG'], 'master_satuan.status'=>1])
            ->indexBy('master_satuan.code')
            ->column();

        $success = true;
        $message = '';
        $model = new MasterBarang();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try {
                    $model->code = $model->generateCode();
                    if(!$model->save()){
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE DATA BARANG: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $transaction->commit();
                        $message = '['.$model->code.'] SUCCESS CREATE BARANG.';
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
                }catch(\Exception $e) {
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
            'satuan' => $satuan,
        ]);
    }

    /**
     * Updates an existing MasterBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $satuan= MasterSatuan::find()
            ->select(['master_satuan.name'])
            ->leftJoin('master_kode', 'master_kode.code = master_satuan.type_satuan')
            ->where(['value'=> \Yii::$app->params['TYPE_BARANG'], 'master_satuan.status'=>1])
            ->indexBy('master_satuan.code')
            ->column();

        $success = true;
        $message = '';
        $model = $this->findModel($code);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            if(!$model->save()){
                $success = false;
                $message = (count($model->errors) > 0) ? 'ERROR UPDATE DATA BARANG: ' : '';
                foreach($model->errors as $error => $value){
                    $message .= $value[0].', ';
                }
                $message = substr($message, 0, -2);
            }

            if($success){
                $transaction->commit();
                $message = '['.$model->code.'] SUCCESS UPDATE BARANG.';
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
        }

        return $this->render('update', [
            'model' => $model,
            'satuan' => $satuan,
        ]);
    }

    /**
     * Deletes an existing MasterBarang model.
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
                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE DATA BARANG: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = '['.$model->code.'] SUCCESS DELETE BARANG.';
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
     * Finds the MasterBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterBarang::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
