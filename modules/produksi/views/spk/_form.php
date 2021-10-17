<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'no_spk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_spk')->textInput() ?>

    <?= $form->field($model, 'no_so')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_so')->textInput() ?>

    <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'status_produksi')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
