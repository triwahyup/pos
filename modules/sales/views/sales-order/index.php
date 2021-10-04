<?php
use app\commands\Helper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sales\models\SalesOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-order-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Sales Order</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'no_so',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'tgl_so',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_so', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_so',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ],
                ]),
            ],
            [
                'attribute' => 'no_po',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'tgl_po',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_po', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_po',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ],
                ]),
            ],
            [
                'attribute' => 'customer_code',
                'value' => function($model, $index, $key)
                {
                    return (isset($model->customer)) ? $model->customer->name : '';
                }
            ],
            [
                'attribute' => 'total_order',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key)
                {
                    return number_format($model->total_order).'.-';
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['eye-open'],
                            ['view', 'no_so'=>$model->no_so],
                            ['title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['pencil'],
                            ['update', 'no_so'=>$model->no_so],
                            ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['trash'],
                            ['delete', 'no_so'=>$model->no_so],
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