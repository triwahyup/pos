<?php

namespace app\modules\pengaturan\controllers;

use app\modules\pengaturan\models\PengaturanMenu;
use app\modules\pengaturan\models\PengaturanMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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
     * Lists all Menu models.
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
     * Displays a single Menu model.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PengaturanMenu();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try {
                    $model->attributes = $model->getAttributes();
                    $model->id = $model->newcode();
                    $model->slug = strtolower(str_replace(' ','-', $model->name));
                    $model->level = 1;
				    $model->parent_id = NULL;
                    if(!empty($model->parent_1)){
                        $model->level = 2;
                        $model->parent_id = $model->parent_1;
                        if(!empty($model->parent_2)){
                            $model->level = 3;
                            $model->parent_id = $model->parent_2;
                        }
                    }
                    if(empty($model->link)){
                        $model->link = '#';
                    }
                    if($model->save()){
                        $transaction->commit();
                        \Yii::$app->session->setFlash('success', 'SUCCESS CREATE MENU '.$model->name);
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else{
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE MENU' : '';
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
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->attributes = $model->getAttributes();
                $model->slug = strtolower(str_replace(' ','-', $model->name));
                $model->level = 1;
                $model->parent_id = NULL;
                if(!empty($model->parent_1)){
                    $model->level = 2;
                    $model->parent_id = $model->parent_1;
                    if(!empty($model->parent_2)){
                        $model->level = 3;
                        $model->parent_id = $model->parent_2;
                    }
                }
                if(empty($model->link)){
                    $model->link = '#';
                }
                if($model->save()){
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', 'SUCCESS UPDATE MENU '.$model->name);
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE MENU' : '';
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
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if($model->delete()){
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'SUCCESS DELETE MENU '.$model->name);
            }else{
                $message = (count($model->errors) > 0) ? 'ERROR DELETE MENU' : '';
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
        return $this->redirect(['index']);
    }

    /**
     * Finds the Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return PengaturanMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PengaturanMenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionParentMenu()
    {
        if(!empty($_POST['parent'])){
            $model = PengaturanMenu::find()
                ->select(['id', 'name'])
                ->where(['level'=>$_POST['level'], 'parent_id'=>$_POST['parent']])
                ->orderBy(['id'=>SORT_ASC])
                ->asArray()
                ->all();
        }else{
            $model = PengaturanMenu::find()
                ->select(['id', 'name'])
                ->where(['level'=>$_POST['level'], 'position'=>$_POST['position']])
                ->orderBy(['id'=>SORT_ASC])
                ->asArray()
                ->all();
        }
        return json_encode($model);
    }
}