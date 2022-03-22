<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterSatuanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Satuan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-satuan-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Data Satuan</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'type_code',
                'value' => function($model, $index, $key){
                    return (isset($model->typeCode)) ? $model->typeCode->name : '';
                }
            ],
            [
                'attribute' => 'type_satuan',
                'value' => function($model, $index, $key){
                    return (isset($model->typeSatuan)) ? $model->typeSatuan->name : '';
                }
            ],
            'um_1',
            'um_2',
            'um_3',
            [
                'attribute' => 'konversi_1',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function ($model, $index) { 
                    return (!empty($model->konversi_1)) ? number_format($model->konversi_1) : null;
                }
            ],
            [
                'attribute' => 'konversi_2',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function ($model, $index) { 
                    return (!empty($model->konversi_2)) ? number_format($model->konversi_2) : null;
                }
            ],
            [
                'attribute' => 'konversi_3',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function ($model, $index) { 
                    return (!empty($model->konversi_3)) ? number_format($model->konversi_3) : null;
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
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fontello icon-trash-4"></i>',
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