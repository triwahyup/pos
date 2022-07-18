<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */

$this->title = $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Sales Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-invoice-view">
    <p>
        <?= Html::a('Update', ['update', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'no_invoice' => $model->no_invoice], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'no_invoice',
            'tgl_invoice',
            'ppn',
            'total_order_material',
            'total_order_bahan',
            'total_biaya_produksi',
            'total_ppn',
            'grand_total',
            'new_total_order_material',
            'new_total_order_bahan',
            'new_total_biaya_produksi',
            'new_total_ppn',
            'new_grand_total',
            'keterangan',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>