<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkRequestItem */

$this->title = 'Update Request Item: ' . $model->no_request;
$this->params['breadcrumbs'][] = ['label' => 'Request Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_request, 'url' => ['view', 'no_request' => $model->no_request]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spk-request-item-update">
    <?= $this->render('_form', [
        'model' => $model,
        'detail' => $detail,
    ]) ?>
</div>