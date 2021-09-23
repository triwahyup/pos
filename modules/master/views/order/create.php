<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterOrder */

$this->title = 'Create Data Order';
$this->params['breadcrumbs'][] = ['label' => 'Data Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-order-create">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>