<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterGroupBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-group-barang-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'acc_persedian_code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'acc_penjualan_code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'acc_hpp_code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="text-right">
        <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>