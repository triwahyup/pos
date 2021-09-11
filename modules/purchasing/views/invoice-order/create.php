<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrderInvoice */

$this->title = 'Create Invoice Order';
$this->params['breadcrumbs'][] = ['label' => 'Invoice Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-invoice-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>