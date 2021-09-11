<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKodeAkun */

$this->title = 'Create Kode Akun';
$this->params['breadcrumbs'][] = ['label' => 'Kode Akun', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-akun-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>