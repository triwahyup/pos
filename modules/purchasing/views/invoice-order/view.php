<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrderInvoice */

$this->title = $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-order-invoice-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_po' => $model->no_invoice], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_po' => $model->no_invoice], [
                'class' => 'btn btn-danger btn-flat btn-sm',
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
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>