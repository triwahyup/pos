<?php
use app\models\User;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\sales\models\SalesInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales Invoice';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-invoice-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[C]')):?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create Sales Invoice</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        </p>
    <?php endif;?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'no_invoice',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'tgl_invoice',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_invoice', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_invoice',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'value' => function($model, $index, $key){
                    return date('d-m-Y', strtotime($model->tgl_invoice));
                }
            ],
            [
                'attribute' => 'new_total_order_material',
                'label' => 'Total Material (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'new_total_order_bahan',
                'label' => 'Total Bahan (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'new_total_biaya_produksi',
                'label' => 'Total Produksi (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'new_total_ppn',
                'label' => 'Total Ppn (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'new_grand_total',
                'label' => 'Grand Total (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'code'=>$model->code ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'code'=>$model->code ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                ],
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action',
                ],
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>
</div>