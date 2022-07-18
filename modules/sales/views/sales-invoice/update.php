<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */

$this->title = 'Update Sales Invoice: ' . $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Sales Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_invoice, 'url' => ['view', 'no_invoice' => $model->no_invoice]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-invoice-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>