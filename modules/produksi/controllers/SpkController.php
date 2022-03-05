<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterMesin;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkSearch;
use app\modules\produksi\models\SpkProduksi;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderProses;
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
                                'index', 'view', 'update', 'delete', 'list-mesin', 'print-preview',
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
        $success = true;
        $message = '';
        $model = $this->findModel($no_spk);
        $column = $model->setListColumn();
        $spk_produksi = new SpkProduksi();
        if($this->request->isPost){
            if($model->load($this->request->post()) && $spk_produksi->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    // SO ITEM
                    $soItem = $model->itemMaterial;
                    print_r($soItem);die;
                    // SO POTONG
                    $soPotong = SalesOrderPotong::findOne(['code'=>$model->no_so, 'urutan'=>$spk_produksi->potong_id]);
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
        }

        return $this->render('update', [
            'model' => $model,
            'spk_produksi' => $spk_produksi,
            'operator' => $column['operator'],
            'so_potong' => $column['so_potong'],
            'so_proses' => $column['so_proses'],
            'type_mesin' => $column['type_mesin'],
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
        $success = true;
		$message = '';
        $model = $this->findModel($no_spk);
        if(isset($model)){
            $connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
            try{
                if(count($model->temps) > 0){
                    \Yii::$app->session->setFlash('error', 'Dokumen ini sudah di Proses Produksi.');
                    return $this->redirect(['index']);
                }else{
                    $model->status = 0;
                    if(!$model->save()){
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR DELETE SPK: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }
                if($success){
                    $transaction->commit();
                    $message = '['.$model->no_spk.'] SUCCESS DELETE SPK.';
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

    public function actionListMesin($type)
    {
        $model = MasterMesin::find()
            ->select(['code', 'name'])
            ->where(['type_code'=>$type, 'status'=>1])
            ->asArray()
            ->all();
        return json_encode($model);
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
