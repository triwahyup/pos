<?php

namespace app\modules\master\controllers;

use app\commands\Konstanta;
use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterAccounts;
use app\modules\master\models\MasterAccountsDetail;
use app\modules\master\models\MasterGroupBarang;
use app\modules\master\models\MasterGroupBarangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * GroupBarangController implements the CRUD actions for MasterGroupBarang model.
 */
class GroupBarangController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('group-barang')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('group-barang')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('group-barang')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('group-barang')),
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
     * Lists all MasterGroupBarang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MasterGroupBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MasterGroupBarang model.
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
     * Creates a new MasterGroupBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $akunPersediaan = MasterAccountsDetail::find()
            ->select(['name', 'accounts_code', 'urutan'])
            ->where(['accounts_code'=>Konstanta::TYPE_PERSEDIAAN, 'status' => 1])
            ->indexBy('urutan')
            ->column();
        $akunPenjualan = MasterAccountsDetail::find()
            ->select(['name', 'accounts_code', 'urutan'])
            ->where(['accounts_code'=>Konstanta::TYPE_PENJUALAN, 'status' => 1])
            ->indexBy('urutan')
            ->column();
        $akunHpp = MasterAccountsDetail::find()
            ->select(['name', 'accounts_code', 'urutan'])
            ->where(['accounts_code'=>Konstanta::TYPE_HPP, 'status' => 1])
            ->indexBy('urutan')
            ->column();
        
        $message = '';
        $success = true;
        $model = new MasterGroupBarang();
        $acc_persediaan_code = MasterAccounts::findOne(['code'=>Konstanta::TYPE_PERSEDIAAN, 'status'=>1]);
        $model->acc_persediaan_code = $acc_persediaan_code->code;
        $acc_penjualan_code = MasterAccounts::findOne(['code'=>Konstanta::TYPE_PENJUALAN, 'status'=>1]);
        $model->acc_penjualan_code = $acc_penjualan_code->code;
        $acc_hpp_code = MasterAccounts::findOne(['code'=>Konstanta::TYPE_HPP, 'status'=>1]);
        $model->acc_hpp_code = $acc_hpp_code->code;
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
			    $transaction = $connection->beginTransaction();
                try{
                    $model->code = $model->newcode();
                    if(!$model->save()){
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR CREATE GROUP BARANG: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $transaction->commit();
                        $message = 'CREATE GROUP BARANG: '.$model->name;
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
            'akunPersediaan' => $akunPersediaan,
            'akunPenjualan' => $akunPenjualan,
            'akunHpp' => $akunHpp
        ]);
    }

    /**
     * Updates an existing MasterGroupBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $code Code
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($code)
    {
        $akunPersediaan = MasterAccountsDetail::find()
            ->select(['name', 'accounts_code', 'urutan'])
            ->where(['accounts_code'=>Konstanta::TYPE_PERSEDIAAN, 'status' => 1])
            ->indexBy('urutan')
            ->column();
        $akunPenjualan = MasterAccountsDetail::find()
            ->select(['name', 'accounts_code', 'urutan'])
            ->where(['accounts_code'=>Konstanta::TYPE_PENJUALAN, 'status' => 1])
            ->indexBy('urutan')
            ->column();
        $akunHpp = MasterAccountsDetail::find()
            ->select(['name', 'accounts_code', 'urutan'])
            ->where(['accounts_code'=>Konstanta::TYPE_HPP, 'status' => 1])
            ->indexBy('urutan')
            ->column();

        $message = '';
        $success = true;
        $model = $this->findModel($code);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR UPDATE GROUP BARANG: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'UPDATE GROUP BARANG: '.$model->name;
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

        return $this->render('update', [
            'model' => $model,
            'akunPersediaan' => $akunPersediaan,
            'akunPenjualan' => $akunPenjualan,
            'akunHpp' => $akunHpp
        ]);
    }

    /**
     * Deletes an existing MasterGroupBarang model.
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
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE GROUP BARANG: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    $transaction->commit();
                    $message = 'DELETE GROUP BARANG:'. $model->name;
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
     * Finds the MasterGroupBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $code Code
     * @return MasterGroupBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($code)
    {
        if (($model = MasterGroupBarang::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
