<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanMenu */

$this->title = 'Create Menu';
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengaturan-menu-create">
    <?= $this->render('_form', [
        'model' => $model,
        'typeMenu' => $typeMenu,
    ]) ?>
</div>