<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternal */

$this->title = 'Update Spk Internal: ' . $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Spk Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_spk, 'url' => ['view', 'no_spk' => $model->no_spk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spk-internal-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>