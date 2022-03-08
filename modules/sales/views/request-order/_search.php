<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'no_request') ?>

    <?= $form->field($model, 'tgl_request') ?>

    <?= $form->field($model, 'no_spk') ?>

    <?= $form->field($model, 'keterangan') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'post') ?>

    <?php // echo $form->field($model, 'status_approval') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
