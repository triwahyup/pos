<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKendaraan */

$this->title = 'Update Master Kendaraan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Master Kendaraan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-kendaraan-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataList' => $dataList,
    ]) ?>
</div>