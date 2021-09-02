<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterKodeType;
use app\modules\master\models\MasterKodeTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * KodeTypeController implements the CRUD actions for MasterKodeType model.
 */
class KodeTypeController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode-type')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode-type')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode-type')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode-type')),
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
     * Lists all MasterKodeType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterKodeTypeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterKodeType model.
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
     * Creates a new MasterKodeType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $message = '';
        $model = new MasterKodeType();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $model->code = $model->newcode();
                    if($model->save()){
                        $transaction->commit();
                        $message = 'CREATE KODE TYPE: '.$model->code.':'.$model->name;
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'code' => $model->code]);
                    }else{
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE KODE TYPE: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                        $transaction->rollBack();
                    }
                }catch(\Exception $e) {
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
        ]);
    }

    /**
     * Updates an existing MasterKodeType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $message = '';
        $model = $this->findModel($code);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                if($model->save()){
                    $transaction->commit();
                    $message = 'UPDATE KODE TYPE: '.$model->code.':'.$model->name;
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);

                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['view', 'code' => $model->code]);
                }else{
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE KODE TYPE: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                    $transaction->rollBack();
                }
            }catch(\Exception $e) {
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
        ]);
    }

    /**
     * Deletes an existing MasterKodeType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($code)
    {
        $message = '';
        $model = $this->findModel($code);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if($model->delete()){
                $message = 'DELETE KODE TYPE: '.$model->name;
                $transaction->commit();
                \Yii::$app->session->setFlash('success', $message);
            }else{
                $message = (count($model->errors) > 0) ? 'ERROR DELETE KODE TYPE' : '';
                foreach($model->errors as $error=>$value){
                    $message .= strtoupper($error).": ".$value[0].', ';
                }
                $message = substr($message,0,-2);
                \Yii::$app->session->setFlash('error', $message);
            }
        }catch (\Exception $e) {
            $message = $e->getMessage();
            \Yii::$app->session->setFlash('error', $message);
            $transaction->rollBack();
        }
        $logs = [
			'type' => Logs::TYPE_USER,
			'description' => $message,
		];
		Logs::addLog($logs);
        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterKodeType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterKodeType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterKodeType::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
