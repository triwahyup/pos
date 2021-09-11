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
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                        'data' => $type,
                        'options' => ['placeholder' => 'Type Barang'],
                    ]) ?>
                <?= $form->field($model, 'qty')->textInput() ?>
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>