<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKodeAkun */

$this->title = 'Update Kode Akun: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Kode Akun', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-kode-akun-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
