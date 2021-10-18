<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrder */

$this->title = 'Update SPK: ' . $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'SPK', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_spk, 'url' => ['view', 'no_spk' => $model->no_spk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spk-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>