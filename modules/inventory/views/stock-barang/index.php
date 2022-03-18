<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Inventory Stock Barang';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-stock-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'barang_code',
                'format' => 'raw',
                'value' => function($model, $index, $key) {
                    return (isset($model->barang)) ? (Html::a('<strong>'.$model->barang->code.'</strong><br />'.$model->barang->name, ['view', 'barang_code' => $model->barang_code, 'supplier_code' => $model->supplier_code])) : '';
                }
            ],
            [
                'attribute' => 'supplier_code',
                'value' => function($model, $index, $key) {
                    return (isset($model->supplier)) ? $model->supplier->name : '';
                }
            ],
            [
                'attribute' => 'stock',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'format' => 'raw',
                'value' => function($model, $value) {
                    return $model->stock;
                }
            ],
        ],
    ]); ?>
</div>