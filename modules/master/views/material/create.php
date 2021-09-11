<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterial */

$this->title = 'Create Data Material';
$this->params['breadcrumbs'][] = ['label' => 'Data Material', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-material-create">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>
</div>