<?php

namespace app\modules\sales\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\sales\models\RequestOrder;
use app\modules\sales\models\SalesInvoice;
use app\modules\sales\models\SalesInvoiceSearch;
use app\modules\sales\models\SalesOrder;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * SalesInvoiceController implements the CRUD actions for SalesInvoice model.
 */
class SalesInvoiceController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'detail-sales-order', 'detail-request-order'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[R]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[U]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[D]')),
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
     * Lists all SalesInvoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalesInvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SalesInvoice model.
     * @param string $no_invoice No Invoice
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($no_invoice)
    {
        return $this->render('view', [
            'model' => $this->findModel($no_invoice),
        ]);
    }

    /**
     * Creates a new SalesInvoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $success = true;
        $message = '';
        $listSalesOrder = SalesOrder::find()
            ->alias('a')
            ->select(['concat(code, " - ", a.name)'])
            ->leftJoin('spk_order b', 'b.no_so = a.code')
            ->where(['post' => 1, 'status_produksi' => 4])
            ->indexBy('code')
            ->column();
        $model = new SalesInvoice();
        if ($this->request->isPost) {
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{

                    return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
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
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'listSalesOrder' => $listSalesOrder,
        ]);
    }

    /**
     * Updates an existing SalesInvoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $no_invoice No Invoice
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($no_invoice)
    {
        $success = true;
        $message = '';
        $listSalesOrder = SalesOrder::find()
            ->alias('a')
            ->select(['concat(code, " - ", a.name)'])
            ->leftJoin('spk_order b', 'b.no_so = a.code')
            ->where(['post' => 1, 'status_produksi' => 4])
            ->indexBy('code')
            ->column();
        $model = $this->findModel($no_invoice);
        if($this->request->isPost){
            if($model->load($this->request->post())){
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{

                    return $this->redirect(['view', 'no_invoice' => $model->no_invoice]);
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
        }else{

        }

        return $this->render('update', [
            'model' => $model,
            'listSalesOrder' => $listSalesOrder,
        ]);
    }

    /**
     * Deletes an existing SalesInvoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $no_invoice No Invoice
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($no_invoice)
    {
        $success = true;
		$message = '';
        $model = $this->findModel($no_invoice);
        if(isset($model)){
            $connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
            try{

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
     * Finds the SalesInvoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $no_invoice No Invoice
     * @return SalesInvoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($no_invoice)
    {
        if (($model = SalesInvoice::findOne($no_invoice)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDetailSalesOrder()
    {
        $data = [];
        $post = $_POST['SalesInvoice'];
        if(isset($post['no_so'])){
            $model = SalesOrder::find()->where(['code'=>$post['no_so']])->all();
            $data = $this->renderAjax('detail_sales_order', [
                'model' => $model
            ]);
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['data'=>$data]);
    }

    public function actionDetailRequestOrder()
    {
        $data = [];
        $post = $_POST['SalesInvoice'];
        if(isset($post['no_so'])){
            $model = RequestOrder::find()->where(['no_so'=>$post['no_so']])->all();
            $data = $this->renderAjax('detail_request_order', [
                'model' => $model
            ]);
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['data'=>$data]);
    }
}