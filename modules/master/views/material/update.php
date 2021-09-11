<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterial */

$this->title = 'Update Data Material: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Material', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-material-update">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>
</div>