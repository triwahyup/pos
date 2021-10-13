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
            'name',
            [
                'attribute' => 'type_order',
                'value' => function($model, $index, $key)
                {
                    return ($model->type_order == 1) ? 'Produk' : 'Jasa';
                }
            ],
            [
                'attribute' => 'total_order',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key)
                {
                    return 'Rp. '.number_format($model->total_order).'.-';
                }
            ],
            [
                'attribute' => 'total_biaya',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key)
                {
                    return 'Rp. '.number_format($model->total_biaya).'.-';
                }
            ],
            [
                'attribute' => 'grand_total',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key)
                {
                    return 'Rp. '.number_format($model->grand_total).'.-';
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['eye-open'],
                            ['view', 'code'=>$model->code],
                            ['title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['pencil'],
                            ['update', 'code'=>$model->code],
                            ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['trash'],
                            ['delete', 'code'=>$model->code],
                            [
                                'title'=>'Delete',
                                'aria-label'=>'Delete', 
                                'data-pjax'=>true,
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]);
                    },
                ],
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action',
                ],
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
</div>