<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMesin */

$this->title = 'Create Master Mesin';
$this->params['breadcrumbs'][] = ['label' => 'Master Mesin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-mesin-create">
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>
</div>