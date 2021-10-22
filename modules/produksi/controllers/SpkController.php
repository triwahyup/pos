<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMesin;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkDetail;
use app\modules\produksi\models\SpkDetailBahan;
use app\modules\produksi\models\SpkDetailProduksi;
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
                            'actions' => ['index', 'view', 'item', 'list-proses', 'list-bahan', 'search', 'list-material'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('spk')),
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
        $spkBahan = new SpkDetailBahan();
        $dataItem = SpkDetail::find()
            ->alias('a')
            ->select(['concat(b.code, "-", b.name)'])
            ->leftJoin('master_material_item b', 'b.code = a.item_code')
            ->where(['a.no_spk'=>$no_spk, 'b.status'=>1])
            ->orderBy(['urutan'=>SORT_ASC])
            ->indexBy('item_code')
            ->column();
        $dataProses = MasterKode::find()
            ->select(['value'])
            ->where(['type'=>\Yii::$app->params['TYPE_MESIN'], 'status'=>1])
            ->indexBy('code')
            ->column();
        
        return $this->render('view', [
            'model' => $this->findModel($no_spk),
            'spkBahan' => $spkBahan,
            'dataItem' => $dataItem,
            'dataProses' => $dataProses,
        ]);
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

    public function actionListMaterial($no_spk, $code)
    {
        if(isset($code)){
            $model = SpkDetail::find()->where(['no_spk'=>$no_spk, 'item_code'=>$code])->asArray()->one();
            return json_encode($model);
        }
    }

    public function actionListProses($no_spk, $code)
    {
        if(isset($code)){
            $model = SpkDetailProduksi::find()->where(['no_spk'=>$no_spk, 'item_code'=>$code, 'status'=>1])->asArray()->all();
            return json_encode($model);
        }
    }

    public function actionListBahan()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select(['a.*', 'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->where(['a.type_code'=>\Yii::$app->params['MATERIAL_BP_CODE'], 'a.status'=>1])
            ->orderBy(['a.code'=>SORT_ASC])
            ->limit(10)
            ->all();
        return json_encode(['data'=>$this->renderPartial('_list_bahan', ['model'=>$model])]);
    }

    public function actionSearch()
    {
        $model = [];
        if(isset($_POST['search'])){
            $model = MasterMaterialItem::find()
                ->alias('a')
                ->select(['a.*', 'b.composite'])
                ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
                ->leftJoin('master_material c', 'c.code = a.material_code AND c.type_code = a.type_code')
                ->where(['a.type_code'=>\Yii::$app->params['MATERIAL_BP_CODE'], 'a.status'=>1])
                ->andWhere('a.code LIKE "%'.$_POST['search'].'%" 
                    OR a.name LIKE "%'.$_POST['search'].'%" 
                    OR c.name  LIKE "%'.$_POST['search'].'%"')
                ->orderBy(['a.code'=>SORT_ASC])
                ->limit(10)
                ->all();
        }
        return json_encode(['data'=>$this->renderPartial('_list_bahan', ['model'=>$model])]);
    }

    public function actionItem()
    {
        $model = MasterMaterialItem::find()
            ->alias('a')
            ->select([
                'a.*',
                'a.code as item_bahan_code',
                'a.name as item_bahan_name',
                'c.code as type_bahan_code',
                'c.name as type_bahan',
                'b.composite'])
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->leftJoin('master_material c', 'c.code = a.material_code and c.type_code = a.type_code')
            ->where(['a.code'=>$_POST['code'], 'a.status'=>1])
            ->asArray()
            ->one();
        return json_encode($model);
    }
}