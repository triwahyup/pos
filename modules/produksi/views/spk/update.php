<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'Update Surat Perintah Kerja: ' . $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Surat Perintah Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_spk, 'url' => ['view', 'no_spk' => $model->no_spk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spk-update">
    <?= $this->render('_form', [
        'model' => $model,
        'operator' => $operator,
        'so_potong' => $so_potong,
        'so_proses' => $so_proses,
        'spk_produksi' => $spk_produksi,
        'type_mesin' => $type_mesin,
    ]) ?>
</div>