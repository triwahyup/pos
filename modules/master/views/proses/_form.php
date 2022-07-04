<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterProses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-proses-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20"></div>
        <div class="col-lg-6 col-md-6 col-xs-12">
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
                    <label>Type:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'type')->widget(Select2::classname(), [
                            'data' => $dataList['proses'],
                            'options' => [
                                'placeholder' => 'Type Ongkos',
                                'class' => 'select2',
                            ],
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Urutan:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'urutan')->textInput()->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Index:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'index')->textInput()->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Harga:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'harga')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                            ]
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Type Mesin:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'mesin_type')->widget(Select2::classname(), [
                            'data' => $dataList['mesin'],
                            'options' => [
                                'placeholder' => 'Pilih Type Mesin',
                                'class' => 'select2',
                            ],
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Keterangan:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2])->label(false) ?>
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