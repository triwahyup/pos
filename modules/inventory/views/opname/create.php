<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryOpname */

$this->title = 'Create Opname';
$this->params['breadcrumbs'][] = ['label' => 'Opname', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-opname-create">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>