<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterialItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-material-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'type_code') ?>

    <?= $form->field($model, 'material_code') ?>

    <?= $form->field($model, 'satuan_code') ?>

    <?php // echo $form->field($model, 'group_material_code') ?>

    <?php // echo $form->field($model, 'group_supplier_code') ?>

    <?php // echo $form->field($model, 'panjang') ?>

    <?php // echo $form->field($model, 'lebar') ?>

    <?php // echo $form->field($model, 'gram') ?>

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
