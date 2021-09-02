<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKodeType */

$this->title = 'Update Master Kode Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Master Kode Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-kode-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
