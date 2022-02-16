<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'Create Sales Order';
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-order-create">
    <?= $this->render('_form', [
        'model' => $model,
        'customer' => $customer,
        'tempPotong' => $tempPotong,
        'tempItem' => $tempItem,
        'typeSatuan' => $typeSatuan,
    ]) ?>
</div>
