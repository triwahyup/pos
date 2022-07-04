<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKendaraan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-kendaraan-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-8 col-md-8 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-8 col-md-8 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                            'data' => $dataList['kendaraan'],
                            'options' => ['placeholder' => 'Type Kendaraan'],
                        ]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-8 col-md-8 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'nopol')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-8 col-md-8 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_handphone')->widget(MaskedInput::classname(), ['mask'=>'9999-99999-999']) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-8 col-md-8 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_sim')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-8 col-md-8 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 3]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="form-group text-right margin-bottom-0 margin-top-30">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>