<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */

$this->title = 'Update Request Order: ' . $model->no_request;
$this->params['breadcrumbs'][] = ['label' => 'Request Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_request, 'url' => ['view', 'no_request' => $model->no_request]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="request-order-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>