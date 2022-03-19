<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalInvoice */

$this->title = 'Update Invoice Internal: ' . $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_invoice, 'url' => ['view', 'no_invoice' => $model->no_invoice]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-internal-invoice-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>