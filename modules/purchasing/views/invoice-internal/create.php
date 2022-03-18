<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalInvoice */

$this->title = 'Create Purchase Internal Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Internal Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-internal-invoice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
