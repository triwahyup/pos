<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKodeType */

$this->title = 'Create Data Kode Type';
$this->params['breadcrumbs'][] = ['label' => 'Data Kode Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
