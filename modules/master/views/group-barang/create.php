<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterGroupBarang */

$this->title = 'Create Group Barang';
$this->params['breadcrumbs'][] = ['label' => 'Group Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-group-barang-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>