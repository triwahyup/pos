<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBiayaProduksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-biaya-produksi-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type')->widget(Select2::classname(), [
                        'data' => [
                            1 => 'CETAK',
                            2 => 'POND',
                        ],
                        'options' => [
                            'placeholder' => 'Type Ongkos',
                            'class' => 'select2',
                        ],
                    ]) ?>
                <?= $form->field($model, 'index')->textInput() ?>
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                
                <div class="form-group margin-top-20">
                    <p class="font-size-10 text-danger">Harga diisi hanya untuk ketentuan berlaku. Misal order kurang dari 20 RIM.</p>
                </div>
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
                    ]) ?>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>