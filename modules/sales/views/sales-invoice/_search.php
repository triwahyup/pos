<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'no_invoice') ?>

    <?= $form->field($model, 'tgl_invoice') ?>

    <?= $form->field($model, 'ppn') ?>

    <?= $form->field($model, 'total_order_material') ?>

    <?= $form->field($model, 'total_order_bahan') ?>

    <?php // echo $form->field($model, 'total_biaya_produksi') ?>

    <?php // echo $form->field($model, 'total_ppn') ?>

    <?php // echo $form->field($model, 'grand_total') ?>

    <?php // echo $form->field($model, 'new_total_order_material') ?>

    <?php // echo $form->field($model, 'new_total_order_bahan') ?>

    <?php // echo $form->field($model, 'new_total_biaya_produksi') ?>

    <?php // echo $form->field($model, 'new_total_ppn') ?>

    <?php // echo $form->field($model, 'new_grand_total') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
