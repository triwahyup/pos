<?php
use app\commands\Helper;
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
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Data Karyawan</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

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
                        return Html::a(Helper::buttonIcons()['eye-open'],
                            ['view', 'user_id'=>$model->user_id],
                            ['title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['pencil'],
                            ['update', 'user_id'=>$model->user_id],
                            ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['trash'],
                            ['delete', 'user_id'=>$model->user_id],
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
                    'switch' => function ($url, $model){
						return Html::a('<i class="fontello icon-retweet"></i><span>Swith User</span>', [
                            'switch', 'user_id'=>$model->user_id
                        ], [
                            'data' => [
                                'confirm' => 'Switch to this user "'.$model->name.'"?',
                                'method' => 'post',
                            ],
                            'class' => 'btn-switch-user',
                            'title' => 'Switch User to '.$model->name,
                        ]);
					},
                ],
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action',
                    'style' => 'width: 200px',
                ],
                'template' => '{view} {update} {delete} {switch}',
            ],
        ],
    ]); ?>
</div>