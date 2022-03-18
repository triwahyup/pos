<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBarang */

$this->title = 'Update Data Barang: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-barang-update">
    <?= $this->render('_form', [
        'model' => $model,
        'satuan' => $satuan,
    ]) ?>
</div>
