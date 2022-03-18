<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternal */

$this->title = 'Update Purchase Order Internal: ' . $model->no_po;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_po, 'url' => ['view', 'no_po' => $model->no_po]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-internal-update">
    <?= $this->render('_form', [
        'supplier' => $supplier,
        'profile' => $profile,
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>