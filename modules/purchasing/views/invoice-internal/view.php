<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalInvoice */

$this->title = $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Internal Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-internal-invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'no_bukti',
            'no_po',
            'tgl_po',
            'tgl_kirim',
            'term_in',
            'supplier_code',
            'keterangan:ntext',
            'total_ppn',
            'total_order',
            'total_invoice',
            'user_id',
            'post',
            'status',
            'status_terima',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
