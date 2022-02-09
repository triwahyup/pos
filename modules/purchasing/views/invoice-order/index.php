<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\purchasing\models\PurchaseOrderInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoice Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-invoice-index">
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
                'format' => 'raw',
                'value' => function($model, $index, $key) {
                    return Html::a($model->no_invoice, ['view', 'no_invoice' => $model->no_invoice]);
                }
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
                'value' => function($model, $index, $key) {
                    return date('d-m-Y', strtotime($model->tgl_invoice));
                }
            ],
            [
                'attribute' => 'no_bukti',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'no_po',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
            ],
            [
                'attribute' => 'supplier_code',
                'value' => function($model, $index, $key) {
                    return (isset($model->supplier)) ? $model->supplier->name : '';
                }
            ],
            [
                'attribute' => 'total_invoice',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $index, $key) {
                    return number_format($model->total_order).'.-';
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
        ],
    ]); ?>
</div>