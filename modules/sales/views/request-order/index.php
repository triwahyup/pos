<?php
use app\models\User;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sales\models\RequestOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Request Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-order-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'no_request',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'tgl_request',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_request', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_request',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key){
                    return date('d-m-Y', strtotime($model->tgl_request));
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
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key)
                {
                    return date('d-m-Y', $model->created_at);
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
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key)
                {
                    return date('d-m-Y', $model->updated_at);
                }
            ],
            [
                'attribute' => 'post',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function($model, $index, $key)
                {
                    return $model->statusPost;
                }
            ],
            [
                'attribute' => 'status_approval',
                'label' => 'Approval',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'format' => 'raw',
                'value' => function ($model, $index, $key) { 
                    return $model->statusApproval;
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'no_request'=>$model->no_request ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'no_request'=>$model->no_request ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[D]'))
                            return Html::a('<i class="fontello icon-trash-4"></i>',
                                [ 'delete', 'no_request'=>$model->no_request ],
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