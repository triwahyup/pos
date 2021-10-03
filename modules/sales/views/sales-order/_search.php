<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'no_so') ?>

    <?= $form->field($model, 'tgl_so') ?>

    <?= $form->field($model, 'no_po') ?>

    <?= $form->field($model, 'tgl_po') ?>

    <?= $form->field($model, 'customer_code') ?>

    <?php // echo $form->field($model, 'ppn') ?>

    <?php // echo $form->field($model, 'total_order') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
