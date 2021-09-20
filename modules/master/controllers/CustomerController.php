<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterProvinsi;
use app\modules\master\models\MasterKabupaten;
use app\modules\master\models\MasterKecamatan;
use app\modules\master\models\MasterKelurahan;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterCustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for MasterPerson model.
 */
class CustomerController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-customer')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-kabupaten', 'list-kecamatan', 'list-kelurahan'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-customer')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-customer')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-customer')),
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
     * Lists all MasterPerson models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterCustomerSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterPerson model.
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
     * Creates a new MasterPerson model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $dataProvinsi = MasterProvinsi::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('id')
            ->column();
        
        $success = true;
        $message = '';
        $model = new MasterPerson();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try {
                    $model->code = $model->generateCode();
                    $model->type_user = \Yii::$app->params['TYPE_CUSTOMER'];
                    $model->phone_1 = str_replace('-', '', $model->phone_1);
                    if(!empty($model->phone_2)){
                        $model->phone_2 = str_replace('-', '', $model->phone_2);
                    }
                    if(!$model->save()){
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE CUSTOMER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                    if($success){
                        $transaction->commit();
                        $message = 'CREATE CUSTOMER: '.$model->name;
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
            'dataProvinsi' => $dataProvinsi,
        ]);
    }

    /**
     * Updates an existing MasterPerson model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $dataProvinsi = MasterProvinsi::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('id')
            ->column();
        
        $success = true;
        $message = '';
        $model = $this->findModel($code);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->phone_1 = str_replace('-', '', $model->phone_1);
                if(!empty($model->phone_2)){
                    $model->phone_2 = str_replace('-', '', $model->phone_2);
                }
                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE CUSTOMER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
                if($success){
                    $transaction->commit();
                    $message = 'UPDATE CUSTOMER: '.$model->name;
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

        return $this->render('update', [
            'model' => $model,
            'dataProvinsi' => $dataProvinsi,
        ]);
    }

    /**
     * Deletes an existing MasterPerson model.
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
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE CUSTOMER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'DELETE CUSTOMER:'. $model->name;
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);
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
     * Finds the MasterPerson model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterPerson the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterPerson::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListKabupaten($provinsiId)
    {
        if(isset($provinsiId)){
            $model = MasterKabupaten::find()
                ->select(['id', 'name'])
                ->where(['provinsi_id'=>$provinsiId, 'status'=>1])
                ->asArray()
                ->all();
            return json_encode($model);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListKecamatan($kecamatanId)
    {
        if(isset($kecamatanId)){
            $model = MasterKecamatan::find()
                ->select(['id', 'name'])
                ->where(['kabupaten_id'=>$kecamatanId, 'status'=>1])
                ->asArray()
                ->all();
            return json_encode($model);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListKelurahan($kelurahanId)
    {
        if(isset($kelurahanId)){
            $model = MasterKelurahan::find()
                ->select(['id', 'name'])
                ->where(['kecamatan_id'=>$kelurahanId, 'status'=>1])
                ->asArray()
                ->all();
            return json_encode($model);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
