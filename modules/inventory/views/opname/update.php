<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryOpname */

$this->title = 'Update Opname: ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Opname', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inventory-opname-update">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
        'supplier' => $supplier,
    ]) ?>
</div>