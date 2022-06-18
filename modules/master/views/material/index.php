<?php
use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Material';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-material-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-material[C]')):?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create Material</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        </p>
    <?php endif;?>

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
                'attribute' => 'type_code',
                'value' => function($model, $index, $key){
                    return (isset($model->typeCode)) ? $model->typeCode->name : '';
                }
            ],
            [
                'attribute' => 'material_code',
                'value' => function($model, $index, $key){
                    return (isset($model->material)) ? $model->material->name : '';
                }
            ],
            [
                'attribute' => 'satuan_code',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function($model, $index, $key){
                    return (isset($model->satuan)) ? $model->satuan->name : '';
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-material[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'code'=>$model->code ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-material[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'code'=>$model->code ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-material[D]'))
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