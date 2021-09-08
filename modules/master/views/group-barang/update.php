<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterGroupBarang */

$this->title = 'Update Group Barang: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Group Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-group-barang-update">
    <?= $this->render('_form', [
        'model' => $model,
        'akunPersediaan' => $akunPersediaan,
        'akunPenjualan' => $akunPenjualan,
        'akunHpp' => $akunHpp
    ]) ?>
</div>