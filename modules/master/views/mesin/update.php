<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMesin */

$this->title = 'Update Data Mesin: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Mesin', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-mesin-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
