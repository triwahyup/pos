<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryBast */

$this->title = 'Create Inventory Bast';
$this->params['breadcrumbs'][] = ['label' => 'Inventory Bast', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-bast-create">
    <?= $this->render('_form', [
        'dataList' => $dataList,
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>