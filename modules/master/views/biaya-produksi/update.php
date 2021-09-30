<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBiayaProduksi */

$this->title = 'Update Biaya Produksi: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Biaya Produksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-biaya-produksi-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>