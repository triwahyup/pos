<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterProses */

$this->title = 'Update Data Proses: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Proses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-proses-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>