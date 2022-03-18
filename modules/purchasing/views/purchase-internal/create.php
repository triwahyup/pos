<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternal */

$this->title = 'Create Purchase Order Internal';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-internal-create">
    <?= $this->render('_form', [
        'supplier' => $supplier,
        'profile' => $profile,
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>