<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Inventory Material Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-material-index">
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
            ],
            [
                'attribute' => 'qty_out',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'qty_retur',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'onhand',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'onsales',
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
        ],
    ]); ?>
</div>