<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterSatuan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-satuan-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                        'data' => $type,
                        'options' => ['placeholder' => 'Type Barang'],
                    ]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'composite')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'um_1')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'um_2')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'um_3')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 3]) ?>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>