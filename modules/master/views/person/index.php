<?php
use app\models\User;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterPersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Person';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-person[C]')): ?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create Data Person</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        </p>
    <?php endif;?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'type_user',
                'value' => function($model, $index, $key){
                    return (isset($model->typePerson)) ? $model->typePerson->name : '';
                }
            ],
            'contact_person',
            [
                'attribute' => 'phone_1',
                'label' => 'Phone',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'term_in',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-person[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'code'=>$model->code ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-person[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'code'=>$model->code ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-person[D]'))
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