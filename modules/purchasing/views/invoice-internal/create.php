<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalInvoice */

$this->title = 'Create Invoice Internal';
$this->params['breadcrumbs'][] = ['label' => 'Invoice Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-internal-invoice-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>