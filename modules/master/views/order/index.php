<?php
use app\commands\Helper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-order-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Data Order</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'keterangan',
            'status',
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
