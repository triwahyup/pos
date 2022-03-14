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
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20"></div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Type Material:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                            'data' => $type,
                            'options' => ['placeholder' => 'Type Barang'],
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Type Satuan:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'type_satuan')->widget(Select2::classname(), [
                            'data' => $satuan,
                            'options' => ['placeholder' => 'Type Satuan'],
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Name:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Composite:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'composite')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Unit of Measure (Um):</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'um_1')->textInput(['maxlength' => true, 'placeholder' => 'Um 1'])->label(false) ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'um_2')->textInput(['maxlength' => true, 'placeholder' => 'Um 2'])->label(false) ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'um_3')->textInput(['maxlength' => true, 'placeholder' => 'Um 3'])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Konversi:</label>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'konversi_1')->textInput(['placeholder' => 'Konv 1'])->label(false) ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'konversi_2')->textInput(['placeholder' => 'Konv 2'])->label(false) ?>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'konversi_3')->textInput(['placeholder' => 'Konv 3'])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Keterangan:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 3])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30"></div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="form-group text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>