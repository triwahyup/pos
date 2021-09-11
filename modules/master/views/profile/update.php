<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\Profile */

$this->title = 'Update Data Karyawan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="profile-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
        'typeUser' => $typeUser,
    ]) ?>
</div>