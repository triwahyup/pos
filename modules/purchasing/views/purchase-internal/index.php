<?php
use app\commands\Helper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\purchasing\models\PurchaseInternalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Order Internal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-internal-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Purchase Order Internal</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'no_po',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'tgl_po',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_po', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_po',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key) {
                    return date('d-m-Y', strtotime($model->tgl_po));
                }
            ],
            [
                'attribute' => 'total_order',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key) {
                    return number_format($model->total_order).'.-';
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
                        return Html::a(Helper::buttonIcons()['eye-open'],
                            ['view', 'no_po'=>$model->no_po],
                            ['title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true]);
                    },
                    'update' => function ($url, $model) {
                        if(\Yii::$app->user->identity->profile->typeUser->value == 'ADMINISTRATOR' 
                            || \Yii::$app->user->identity->profile->typeUser->value == 'ADMIN'){
                            return Html::a(Helper::buttonIcons()['pencil'],
                                ['update', 'no_po'=>$model->no_po],
                                ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                        }
                    },
                    'delete' => function ($url, $model) {
                        if(\Yii::$app->user->identity->profile->typeUser->value == 'ADMINISTRATOR' 
                            || \Yii::$app->user->identity->profile->typeUser->value == 'ADMIN'){
                            return Html::a(Helper::buttonIcons()['trash'],
                                ['delete', 'no_po'=>$model->no_po],
                                [
                                    'title'=>'Delete',
                                    'aria-label'=>'Delete', 
                                    'data-pjax'=>true,
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]);
                        }
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