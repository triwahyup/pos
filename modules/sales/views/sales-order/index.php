<?php
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
                'attribute' => 'code',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            'name',
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
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key){
                    return date('d-m-Y', strtotime($model->tgl_so));
                }
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
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key){
                    return date('d-m-Y', strtotime($model->tgl_po));
                }
            ],
            [
                'attribute' => 'customer_code',
                'value' => function($model, $index, $key)
                {
                    return (isset($model->customer)) ? $model->customer->name : '';
                }
            ],
            [
                'attribute' => 'post',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function($model, $index, $key)
                {
                    return $model->statusPost;
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fontello icon-eye-1"></i>',
                            ['view', 'code'=>$model->code],
                            ['title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fontello icon-pencil-3"></i>',
                                ['update', 'code'=>$model->code],
                                ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                    },
                ],
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action',
                ],
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>


</div>
