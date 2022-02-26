<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'Create Surat Perintah Kerja';
$this->params['breadcrumbs'][] = ['label' => 'Surat Perintah Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>