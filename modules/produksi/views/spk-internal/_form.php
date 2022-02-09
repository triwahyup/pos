<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-internal-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($model, 'no_spk')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'tgl_spk')->textInput() ?>
                <?= $form->field($model, 'no_so')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'tgl_so')->textInput() ?>
                <?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>