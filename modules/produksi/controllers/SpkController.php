<?php

namespace app\modules\produksi\controllers;

use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SpkController implements the CRUD actions for Spk model.
 */
class SpkController extends Controller
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
     * Lists all Spk models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Spk model.
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
     * Creates a new Spk model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Spk();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'no_spk' => $model->no_spk]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Spk model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_spk No Spk
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_spk)
    {
        $model = $this->findModel($no_spk);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'no_spk' => $model->no_spk]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Spk model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_spk No Spk
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_spk)
    {
        $this->findModel($no_spk)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Spk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_spk No Spk
     * @return Spk the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_spk)
    {
        if (($model = Spk::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
