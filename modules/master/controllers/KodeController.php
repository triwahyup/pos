<?php

namespace app\modules\master\controllers;

use app\commands\Helper;
use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\AuthAssignment;
use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterKodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * KodeController implements the CRUD actions for MasterKode model.
 */
class KodeController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-kode')),
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
     * Lists all MasterKode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterKodeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterKode model.
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
     * Creates a new MasterKode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $model = new MasterKode();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try {
                    $model->code = $model->newcode();
                    if($model->save()){
                        $auth = \Yii::$app->authManager;
                        $author = $auth->createRole(strtolower($model->type).'#'.$model->code);
                        if($model->type == Helper::TYPE_USER){
                            if(!$auth->add($author)){
                                $success = false;
                                $message = 'ERROR CREATE AUTH (MASTER KODE)';
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE KODE: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $transaction->commit();
                        $message = 'CREATE KODE: '.$model->code.':'.$model->name;
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
        ]);
    }

    /**
     * Updates an existing MasterKode model.
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
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE KODE: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'UPDATE KODE: '.$model->code.':'.$model->name;
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
        ]);
    }

    /**
     * Deletes an existing MasterKode model.
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
                AuthItemChild::deleteAll("parent='".$model->type."#".$model->code."' OR child='".$model->type."#".$model->code."'");
                AuthAssignment::deleteAll(['item_name' => $model->type."#".$model->code]);
                AuthItem::deleteAll(['name' => $model->type."#".$model->code]);

                $message = 'DELETE KODE: '.$model->name;
                $transaction->commit();
                \Yii::$app->session->setFlash('success', $message);
            }else{
                $message = (count($model->errors) > 0) ? 'ERROR DELETE KODE' : '';
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
     * Finds the MasterKode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterKode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterKode::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
