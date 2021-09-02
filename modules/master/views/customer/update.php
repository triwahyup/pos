<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = 'Update Master Customer: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Master Customer', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-person-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
    ]) ?>
</div>
