<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'Form Surat Perintah Kerja: ' . $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Surat Perintah Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_spk, 'url' => ['view', 'no_spk' => $model->no_spk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spk-layar">
    <?= $this->render('_form', [
        'dataList' => $dataList,
        'model' => $model,
        'spkHistory' => $spkHistory,
    ]) ?>
</div>