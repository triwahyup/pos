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
                    return (isset($model->item)) ? (Html::a('<strong>'.$model->item->code.'</strong><br />'.$model->item->name, ['view', 'item_code' => $model->item_code, 'supplier_code' => $model->supplier_code])) : '';
                }
            ],
            [
                'attribute' => 'supplier_code',
                'value' => function($model, $index, $key) {
                    return (isset($model->supplier)) ? $model->supplier->name : '';
                }
            ],
            [
                'attribute' => 'onhand',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'format' => 'raw',
                'value' => function($model, $value) {
                    return '<strong>'.number_format($model->onhand) .'</strong><br />'. $model->konversi($model->item, $model->onhand);
                }
            ],
            [
                'attribute' => 'onsales',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'format' => 'raw',
                'value' => function($model, $value) {
                    return '<strong>'.number_format($model->onsales) .'</strong><br />'. $model->konversi($model->item, $model->onsales);
                }
            ],
        ],
    ]); ?>
</div>