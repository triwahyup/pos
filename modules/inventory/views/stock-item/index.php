<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Inventory Stock Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-stock-index">
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'item_code',
                'format' => 'raw',
                'value' => function($model, $index, $key) {
                    return (isset($model->item)) ? '<strong>'.$model->item->code.'</strong><br />'.$model->item->name : '';
                }
            ],
            [
                'attribute' => 'qty_in',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $value) {
                    return number_format($model->qty_in);
                }
            ],
            [
                'attribute' => 'qty_out',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $value) {
                    return number_format($model->qty_out);
                }
            ],
            [
                'attribute' => 'qty_retur',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $value) {
                    return number_format($model->qty_retur);
                }
            ],
            [
                'attribute' => 'onhand',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $value) {
                    return number_format($model->onhand);
                }
            ],
            [
                'attribute' => 'onsales',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'value' => function($model, $value) {
                    return number_format($model->onsales);
                }
            ],
        ],
    ]); ?>
</div>