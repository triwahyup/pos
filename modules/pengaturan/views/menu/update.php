<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanMenu */

$this->title = 'Update Menu: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pengaturan-menu-update">
    <?= $this->render('_form', [
        'model' => $model,
        'typeMenu' => $typeMenu,
    ]) ?>
</div>