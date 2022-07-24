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
                        'format' => 'yyyy-mm-dd',
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
                'value' => function($model, $index, $key){
                    return (!empty($model->new_total_order_material)) ? number_format($model->new_total_order_material).'.-' : number_format($model->total_order_material).'.-';
                }
            ],
            [
                'attribute' => 'new_total_order_bahan',
                'label' => 'Total Bahan (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key){
                    return (!empty($model->new_total_order_bahan)) ? number_format($model->new_total_order_bahan).'.-' : number_format($model->total_order_bahan).'.-';
                }
            ],
            [
                'attribute' => 'new_total_biaya_produksi',
                'label' => 'Total Produksi (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key){
                    return (!empty($model->new_total_biaya_produksi)) ? number_format($model->new_total_biaya_produksi).'.-' : number_format($model->total_biaya_produksi).'.-';
                }
            ],
            [
                'attribute' => 'new_total_ppn',
                'label' => 'Total Ppn (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key){
                    return (!empty($model->new_total_ppn)) ? number_format($model->new_total_ppn).'.-' : number_format($model->total_ppn).'.-';
                }
            ],
            [
                'attribute' => 'new_grand_total',
                'label' => 'Grand Total (Rp)',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key){
                    return (!empty($model->new_grand_total)) ? number_format($model->new_grand_total).'.-' : number_format($model->grand_total).'.-';
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[R]'))
                            return Html::a('<i class="fontello icon-eye-1"></i>',
                                [ 'view', 'no_invoice'=>$model->no_invoice ],
                                [ 'title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'update' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[U]'))
                            return Html::a('<i class="fontello icon-pencil-3"></i>',
                                [ 'update', 'no_invoice'=>$model->no_invoice ],
                                [ 'title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true ]
                            );
                        else return "";
                    },
                    'delete' => function ($url, $model) {
                        if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[D]'))
                            return Html::a('<i class="fontello icon-trash-4"></i>',
                                [ 'delete', 'no_invoice'=>$model->no_invoice ],
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