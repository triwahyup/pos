<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMesin */

$this->title = 'Create Data Mesin';
$this->params['breadcrumbs'][] = ['label' => 'Data Mesin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-mesin-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
