<?php

namespace app\modules\inventory\controllers;

use app\models\User;
use app\modules\inventory\models\InventoryStockBarang;
use app\modules\inventory\models\InventoryStockBarangSearch;
use app\modules\inventory\models\InventoryStockBast;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class StockBarangController extends Controller
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
                            'actions' => ['index', 'view'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-barang')),
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
     * Lists all InventoryStockBarang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventoryStockBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($barang_code, $supplier_code)
    {
        $model = InventoryStockBarang::findOne(['barang_code'=>$barang_code, 'supplier_code'=>$supplier_code]);
        $transaction = InventoryStockBast::findAll(['barang_code'=>$barang_code, 'supplier_code'=>$supplier_code]);
        return $this->render('view', [
            'model' => $model,
            'transaction' => $transaction,
        ]);
    }
}