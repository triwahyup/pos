<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\produksi\models\SpkPotongRollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Potong Roll';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-potong-roll-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Potong Roll</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
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
            'item_code',
            'supplier_code',
            'type_code',
            [
                'attribute' => 'post',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'format' => 'raw',
                'value' => function ($model, $index, $key) { 
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