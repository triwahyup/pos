<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterSatuan */

$this->title = 'Create Data Satuan';
$this->params['breadcrumbs'][] = ['label' => 'Data Satuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-satuan-create">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>
</div>