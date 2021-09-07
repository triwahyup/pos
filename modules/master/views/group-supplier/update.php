<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterGroupSupplier */

$this->title = 'Update Group Supplier: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Group Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-group-supplier-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>