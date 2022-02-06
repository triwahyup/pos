<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-internal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'no_spk') ?>

    <?= $form->field($model, 'tgl_spk') ?>

    <?= $form->field($model, 'no_so') ?>

    <?= $form->field($model, 'tgl_so') ?>

    <?= $form->field($model, 'keterangan_cetak') ?>

    <?php // echo $form->field($model, 'keterangan_potong') ?>

    <?php // echo $form->field($model, 'keterangan_pond') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_produksi') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>