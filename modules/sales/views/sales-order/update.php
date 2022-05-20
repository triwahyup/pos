<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'Update Sales Order: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-order-update">
    <?= $this->render('_form', [
        'model' => $model,
        'customer' => $customer,
        'ekspedisi' => $ekspedisi,
        'tempPotong' => $tempPotong,
        'tempItem' => $tempItem,
        'typeSatuan' => $typeSatuan,
    ]) ?>
</div>