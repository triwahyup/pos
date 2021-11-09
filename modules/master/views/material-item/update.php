<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterialItem */

$this->title = 'Update Material Item: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Material Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-material-item-update">
    <?= $this->render('_form', [
        'temp' => $temp,
        'model' => $model,
        'type' => $type,
        'material' => $material,
        'satuan' => $satuan,
        'groupMaterial' => $groupMaterial,
        'groupSupplier' => $groupSupplier,
    ]) ?>
</div>