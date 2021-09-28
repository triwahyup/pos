<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterialItem */

$this->title = 'Create Material Item';
$this->params['breadcrumbs'][] = ['label' => 'Material Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-material-item-create">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
        'material' => $material,
        'satuan' => $satuan,
        'groupMaterial' => $groupMaterial,
        'groupSupplier' => $groupSupplier,
    ]) ?>
</div>