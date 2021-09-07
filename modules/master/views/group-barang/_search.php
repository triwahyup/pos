<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterGroupBarangSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-group-barang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'acc_persedian_code') ?>

    <?= $form->field($model, 'acc_persedian_urutan') ?>

    <?= $form->field($model, 'acc_penjualan_code') ?>

    <?php // echo $form->field($model, 'acc_penjualan_urutan') ?>

    <?php // echo $form->field($model, 'acc_hpp_code') ?>

    <?php // echo $form->field($model, 'acc_hpp_urutan') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
