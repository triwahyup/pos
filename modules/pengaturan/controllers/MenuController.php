<?php

namespace app\modules\pengaturan\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\pengaturan\models\PengaturanMenu;
use app\modules\pengaturan\models\PengaturanMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * MenuController implements the CRUD actions for PengaturanMenu model.
 */
class MenuController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('menu')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('menu')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('menu')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('menu')),
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
     * Lists all PengaturanMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PengaturanMenuSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PengaturanMenu model.
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
     * Creates a new PengaturanMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $typeMenu = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_MENU'], 'status'=>1])
            ->indexBy('code')
            ->column();
        
        $success = true;
        $message = '';
        $model = new PengaturanMenu();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try {
                    $model->code = $model->generateCode();
                    $model->slug = strtolower(str_replace(' ','-', $model->name));
                    $model->level = 1;
				    $model->parent_code = NULL;
                    if(!empty($model->parent_1)){
                        $model->level = 2;
                        $model->parent_code = $model->parent_1;
                        if(!empty($model->parent_2)){
                            $model->level = 3;
                            $model->parent_code = $model->parent_2;
                        }
                    }
                    if(empty($model->link)){
                        $model->link = '#';
                    }

                    if($model->save()){
                        $auth = \Yii::$app->authManager;
                        $author = $auth->createRole($model->slug);
					    $auth->add($author);
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE MENU : ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $transaction->commit();
                        $message = 'CREATE MENU: '.$model->name.', SLUG: '.$model->slug;
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
                $logs = [
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
            'typeMenu' => $typeMenu,
        ]);
    }

    /**
     * Updates an existing PengaturanMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $typeMenu = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_MENU'], 'status'=>1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $model = $this->findModel($code);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->level = 1;
                $model->parent_code = NULL;
                if(!empty($model->parent_1)){
                    $model->level = 2;
                    $model->parent_code = $model->parent_1;
                    if(!empty($model->parent_2)){
                        $model->level = 3;
                        $model->parent_code = $model->parent_2;
                    }
                }
                if(empty($model->link)){
                    $model->link = '#';
                }

                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE MENU : ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'UPDATE MENU: '.$model->name.', SLUG: '.$model->slug;
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
            'typeMenu' => $typeMenu,
        ]);
    }

    /**
     * Deletes an existing PengaturanMenu model.
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
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE MENU: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'DELETE MENU: '.$model->name;
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
     * Finds the PengaturanMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return PengaturanMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = PengaturanMenu::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionList()
    {
        if(!empty($_POST['parent'])){
            $model = PengaturanMenu::find()
                ->select(['code', 'name'])
                ->where(['level'=>$_POST['level'], 'parent_code'=>$_POST['parent']])
                ->orderBy(['code'=>SORT_ASC])
                ->asArray()
                ->all();
        }else{
            $model = PengaturanMenu::find()
                ->select(['code', 'name'])
                ->where(['level'=>$_POST['level'], 'type_code'=>$_POST['position']])
                ->orderBy(['code'=>SORT_ASC])
                ->asArray()
                ->all();
        }
        return json_encode($model);
    }
}
