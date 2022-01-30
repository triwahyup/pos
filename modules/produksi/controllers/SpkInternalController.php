<?php
namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\produksi\models\SpkInternal;
use app\modules\produksi\models\SpkInternalDetail;
use app\modules\produksi\models\SpkInternalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SpkInternalController implements the CRUD actions for SpkInternal model.
 */
class SpkInternalController extends Controller
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
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('spk-internal')),
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
     * Lists all SpkInternal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SpkInternalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SpkInternal model.
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
     * Creates a new SpkInternal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SpkInternal();
        $detail = new SpkInternalDetail();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'no_spk' => $model->no_spk]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'detail' => $detail,
        ]);
    }

    /**
     * Updates an existing SpkInternal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_spk No Spk
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_spk)
    {
        $model = $this->findModel($no_spk);
        $detail = new SpkInternalDetail();
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'no_spk' => $model->no_spk]);
        }

        return $this->render('update', [
            'model' => $model,
            'detail' => $detail,
        ]);
    }

    /**
     * Deletes an existing SpkInternal model.
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
     * Finds the SpkInternal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_spk No Spk
     * @return SpkInternal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_spk)
    {
        if (($model = SpkInternal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
