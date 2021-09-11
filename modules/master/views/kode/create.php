<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKode */

$this->title = 'Create Data Kode';
$this->params['breadcrumbs'][] = ['label' => 'Data Kode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>