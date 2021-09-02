<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKode */

$this->title = 'Create Master Kode';
$this->params['breadcrumbs'][] = ['label' => 'Master Kode', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kode-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
