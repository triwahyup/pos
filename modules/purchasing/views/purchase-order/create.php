<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrder */

$this->title = 'Create Purchase Order';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-create">
    <?= $this->render('_form', [
        'model' => $model,
        'supplier' => $supplier,
        'profile' => $profile,
        'temp' => $temp,
    ]) ?>
</div>