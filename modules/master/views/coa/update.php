<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterCoa */

$this->title = 'Update Master Coa: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Master Coa', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-coa-update">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>