<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterCoa */

$this->title = 'Create Master Coa';
$this->params['breadcrumbs'][] = ['label' => 'Master Coa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-coa-create">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>