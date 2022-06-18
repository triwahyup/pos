<?php
use app\models\User;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Karyawan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[C]')): ?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create Data Karyawan</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'phone_1',
                'label' => 'Phone',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'typeuser_code',
                'value' => function($model, $index, $key) {
                    return (isset($model->typeUser)) ? $model->typeUser->name : NULL;
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'user_id'=>$model->user_id ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'user_id'=>$model->user_id ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[D]'))
                            return Html::a('<i class="fontello icon-trash-4"></i>',
                                [ 'delete', 'user_id'=>$model->user_id ],
                                [ 'title'=>'Delete', 'aria-label'=>'Delete', 'data-pjax'=>true,
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]
                            );
                        else return "";
                    },
                    'switch' => function ($url, $model){
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[U]'))
                            return Html::a('<i class="fontello icon-retweet"></i><span>Swith User</span>',
                                [ 'switch', 'user_id'=>$model->user_id ],
                                [ 'class' => 'btn-switch-user', 'style' => 'top: -6px', 'title' => 'Switch User to '.$model->name,
                                    'data' => [
                                        'confirm' => 'Switch to this user "'.$model->name.'"?',
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
                    'style' => 'width: 200px;',
                ],
                'template' => '{view} {update} {delete} {switch}',
            ],
        ],
    ]); ?>
</div>