<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\commands\Helper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterKodeTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Kode Type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-type-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Master Kode Type</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
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
                'attribute' => 'created_at',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
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