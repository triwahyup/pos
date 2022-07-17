<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrderInvoice */

$this->title = 'Update Invoice Order: ' . $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_invoice, 'url' => ['view', 'no_invoice' => $model->no_invoice]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-order-invoice-update">
    <?= $this->render('_form', [
        'dataList' => $dataList,
        'model' => $model,
    ]) ?>
</div>