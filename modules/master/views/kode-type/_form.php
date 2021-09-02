<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKodeType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-kode-type-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="text-right">
        <div class="form-group">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>