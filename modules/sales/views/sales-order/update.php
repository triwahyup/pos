<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'Update Sales Order: ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-order-update">
    <?= $this->render('_form', [
        'model' => $model,
        'customer' => $customer,
        'tempPotong' => $tempPotong,
        'tempItem' => $tempItem,
        'typeSatuan' => $typeSatuan,
        'itemTemp' => $itemTemp,
    ]) ?>
</div>