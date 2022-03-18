<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalInvoice */

$this->title = 'Update Purchase Internal Invoice: ' . $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Internal Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_invoice, 'url' => ['view', 'no_invoice' => $model->no_invoice]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-internal-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
