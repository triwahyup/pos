<?php
use app\commands\Helper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pengaturan\models\PengaturanMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengaturan Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-menu-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Menu</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'link',
            [
                'attribute' => 'type_code',
                'value' => function ($model, $key, $index) {
                    return (isset($model->typeCode)) ? $model->typeCode->name : '-';
                }
            ],
            [
                'attribute' => 'parent_code',
                'value' => function ($model, $key, $index) {
                    return (isset($model->parent)) ? $model->parent->name : '-';
                }
            ],
            [
                'attribute' => 'level',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'urutan',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
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