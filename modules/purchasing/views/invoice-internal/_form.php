<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-internal-invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no_invoice')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_invoice')->textInput() ?>

    <?= $form->field($model, 'no_bukti')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_po')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_po')->textInput() ?>

    <?= $form->field($model, 'tgl_kirim')->textInput() ?>

    <?= $form->field($model, 'term_in')->textInput() ?>

    <?= $form->field($model, 'supplier_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'total_ppn')->textInput() ?>

    <?= $form->field($model, 'total_order')->textInput() ?>

    <?= $form->field($model, 'total_invoice')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'post')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'status_terima')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
