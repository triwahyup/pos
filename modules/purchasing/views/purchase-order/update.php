<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrder */

$this->title = 'Update Purchase Order: ' . $model->no_po;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_po, 'url' => ['view', 'no_po' => $model->no_po]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-order-update">
    <?= $this->render('_form', [
        'model' => $model,
        'supplier' => $supplier,
        'profile' => $profile,
        'temp' => $temp,
    ]) ?>
</div>