<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBarang */

$this->title = 'Create Data Barang';
$this->params['breadcrumbs'][] = ['label' => 'Data Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-barang-create">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>
</div>
