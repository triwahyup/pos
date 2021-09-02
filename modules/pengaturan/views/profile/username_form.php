<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="ganti-profile-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($profile, 'name')->textInput(['maxlength' => true])->label('Name (Profile / Alias)') ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($profile, 'typeuser_code')->widget(Select2::classname(), ['data' => $typeCode]) ?>
        </div>
    </div>
    <div>
        <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>