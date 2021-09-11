<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanApproval */

$this->title = 'Update Pengaturan Approval: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Approval', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengaturan-approval-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>