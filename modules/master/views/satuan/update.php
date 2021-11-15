<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterSatuan */

$this->title = 'Update Data Satuan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Satuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-satuan-update">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
        'satuan' => $satuan,
    ]) ?>
</div>