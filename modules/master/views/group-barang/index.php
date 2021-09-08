<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use app\commands\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterGroupBarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Group Barang';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-group-barang-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Group Barang</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'acc_persediaan_code',
                'value' => function($model, $value) {
                    return (isset($model->persediaan)) ? $model->persediaan->name : '';
                }
            ],
            [
                'attribute' => 'acc_penjualan_code',
                'value' => function($model, $value) {
                    return (isset($model->penjualan)) ? $model->penjualan->name : '';
                }
            ],
            [
                'attribute' => 'acc_hpp_code',
                'value' => function($model, $value) {
                    return (isset($model->hpp)) ? $model->hpp->name : '';
                }
            ],
            [
                'attribute' => 'created_at',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'created_at', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'created_at',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key)
                {
                    return date('Y-m-d', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'updated_at', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'updated_at',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key)
                {
                    return date('Y-m-d', $model->updated_at);
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