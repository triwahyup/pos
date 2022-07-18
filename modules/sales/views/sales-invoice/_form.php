<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-invoice-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'no_invoice')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'tgl_invoice')->textInput() ?>
    <?= $form->field($model, 'ppn')->textInput() ?>
    <?= $form->field($model, 'total_order_material')->textInput() ?>
    <?= $form->field($model, 'total_order_bahan')->textInput() ?>
    <?= $form->field($model, 'total_biaya_produksi')->textInput() ?>
    <?= $form->field($model, 'total_ppn')->textInput() ?>
    <?= $form->field($model, 'grand_total')->textInput() ?>
    <?= $form->field($model, 'new_total_order_material')->textInput() ?>
    <?= $form->field($model, 'new_total_order_bahan')->textInput() ?>
    <?= $form->field($model, 'new_total_biaya_produksi')->textInput() ?>
    <?= $form->field($model, 'new_total_ppn')->textInput() ?>
    <?= $form->field($model, 'new_grand_total')->textInput() ?>
    <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>