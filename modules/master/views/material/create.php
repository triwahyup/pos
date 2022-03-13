<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterial */

$this->title = 'Create Material';
$this->params['breadcrumbs'][] = ['label' => 'Material', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-material-create">
    <?= $this->render('_form', [
        'temp' => $temp,
        'model' => $model,
        'type' => $type,
        'material' => $material,
        'satuan' => $satuan,
        'supplier' => $supplier,
    ]) ?>
</div>