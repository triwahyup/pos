<?php
use app\models\User;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\inventory\models\InventoryOpnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opname';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-opname-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[C]')): ?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create Opname</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
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
            [
                'attribute' => 'supplier_code',
                'value' => function($model, $index, $key)
                {
                    return (isset($model->supplier)) ? $model->supplier->name : '-';
                }
            ],
            [
                'attribute' => 'date',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'date', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'date',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key)
                {
                    return date('d-m-Y', strtotime($model->date));
                }
            ],
            [
                'attribute' => 'status_approval',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'format' => 'raw',
                'value' => function ($model, $index, $key) { 
                    return $model->statusApproval;
                }
            ],
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
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'code'=>$model->code ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'code'=>$model->code ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('stock-opname[D]'))
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