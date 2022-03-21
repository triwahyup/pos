<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryBast */

$this->title = 'Update Inventory Bast: ' . $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Inventory Bast', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inventory-bast-update">
    <?= $this->render('_form', [
        'model' => $model,
        'profile' => $profile,
        'temp' => $temp,
        'type' => $type,
    ]) ?>
</div>