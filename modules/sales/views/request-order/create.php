<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */

$this->title = 'Create Request Order';
$this->params['breadcrumbs'][] = ['label' => 'Request Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-order-create">
    <?= $this->render('_form', [
        'model' => $model,
        'sorder' => $sorder,
        'temp' => $temp,
    ]) ?>
</div>