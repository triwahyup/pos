<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrderInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-order-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'no_invoice') ?>

    <?= $form->field($model, 'tgl_invoice') ?>

    <?= $form->field($model, 'no_bukti') ?>

    <?= $form->field($model, 'no_po') ?>

    <?= $form->field($model, 'tgl_po') ?>

    <?php // echo $form->field($model, 'tgl_kirim') ?>

    <?php // echo $form->field($model, 'term_in') ?>

    <?php // echo $form->field($model, 'supplier_code') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'total_ppn') ?>

    <?php // echo $form->field($model, 'total_order') ?>

    <?php // echo $form->field($model, 'total_invoice') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'post') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
