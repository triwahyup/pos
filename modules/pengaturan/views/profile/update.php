<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\Profile */

$this->title = 'Update Profile: ' . $model->name;
$this->params['breadcrumbs'][] = 'Update Profile';
?>
<div class="profile-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
        'typeUser' => $typeUser,
    ]) ?>
</div>