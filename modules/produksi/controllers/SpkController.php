<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
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
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => [
                                'index', 'view', 'update', 'delete', 'list-print', 'print-preview'
                            ],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('surat-perintah-kerja')),
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
        if (($model = Spk::findOne($no_spk)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListPrint($no_spk)
    {
        $data = [
            'spk_potong' => 'SPK Potong',
            'spk_cetak' => 'SPK Cetak',
            'spk_pond' => 'SPK Pond',
            'spk_pretel' => 'SPK Pretel',
            'spk_lem' => 'SPK Lem',
            'spk_pengiriman' => 'SPK Pengiriman',
        ];
        $model = $this->findModel($no_spk);
        return json_encode(['data'=>$this->renderPartial('_list_print', [
            'data'=>$data, 'model'=>$model])
        ]);
    }

    public function actionPrintPreview()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        $data = [];
        if($request->isPost){
            $model = $this->findModel($request->post('no_spk'));
            $data = $this->renderPartial('_preview', [
                'model'=>$model,
                'layouts'=>$request->post('spk_print'),
            ]);
        }else{
            $success = false;
            $message = 'The requested data does not exist.';
        }
        return json_encode(['success'=>$success, 'message'=>$message, 'data'=>$data]);
    }
}
