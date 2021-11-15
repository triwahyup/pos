<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-internal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'no_pi') ?>

    <?= $form->field($model, 'tgl_pi') ?>

    <?= $form->field($model, 'keterangan') ?>

    <?= $form->field($model, 'total_order') ?>

    <?= $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'user_request') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_approval') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
