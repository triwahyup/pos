<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkRequestItem */

$this->title = 'Create Request Item';
$this->params['breadcrumbs'][] = ['label' => 'Request Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-request-item-create">
    <?= $this->render('_form', [
        'model' => $model,
        'detail' => $detail,
    ]) ?>
</div>