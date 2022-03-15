<?php

namespace app\modules\inventory\controllers;

use app\models\User;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\inventory\models\InventoryStockItemSearch;
use app\modules\inventory\models\InventoryStockTransaction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class StockItemController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-material')),
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
     * Lists all InventoryStockItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventoryStockItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($item_code, $supplier_code)
    {
        $model = InventoryStockItem::findOne(['item_code'=>$item_code, 'supplier_code'=>$supplier_code]);
        $transaction = InventoryStockTransaction::findAll(['item_code'=>$item_code, 'supplier_code'=>$supplier_code]);
        return $this->render('view', [
            'model' => $model,
            'transaction' => $transaction,
        ]);
    }
}