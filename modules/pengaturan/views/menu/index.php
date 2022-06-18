<?php
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pengaturan\models\PengaturanMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengaturan Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-menu-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-menu[C]')): ?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create Menu</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        </p>
    <?php endif; ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'link',
            [
                'attribute' => 'type_code',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'value' => function ($model, $key, $index) {
                    return (isset($model->typeKode)) ? $model->typeKode->name : '-';
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
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-menu[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'code'=>$model->code ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-menu[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'code'=>$model->code ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-menu[D]'))
                            return Html::a('<i class="fontello icon-trash-4"></i>',
                                [ 'delete', 'code'=>$model->code ],
                                [ 'title'=>'Delete', 'aria-label'=>'Delete', 'data-pjax'=>true,
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]
                            );
                        else return "";
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