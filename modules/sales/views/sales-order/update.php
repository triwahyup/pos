<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'Update Sales Order: ' . $model->no_so;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_so, 'url' => ['view', 'no_so' => $model->no_so]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-order-update">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
        'customer' => $customer,
        'outsourcing' => $outsourcing,
    ]) ?>
</div>