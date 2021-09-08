<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanApproval */

$this->title = 'Create Pengaturan Approval';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Approval', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-approval-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
