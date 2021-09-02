<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKodeType */

$this->title = 'Create Master Kode Type';
$this->params['breadcrumbs'][] = ['label' => 'Master Kode Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
