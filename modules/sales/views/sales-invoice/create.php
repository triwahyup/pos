<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */

$this->title = 'Create Sales Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Sales Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-invoice-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listSalesOrder' => $listSalesOrder,
    ]) ?>
</div>