<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-order-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 hidden">
                <?= $form->field($model, 'no_request')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_request')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'dd-mm-yyyy',
                            'value' => date('d-m-Y'),
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_spk')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'keterangan')->textarea(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
        <!-- Form Input Detail -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30">
            <h4>Form Add Item Material / Bahan</h4>
            <hr>
        </div>
        <!-- /Form Input Detail -->
        <!-- Detail List Item -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30">
            <h4>Detail Item Material / Bahan</h4>
            <hr>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">No.</th>
                        <th class="text-center" colspan="2">Item</th>
                        <th class="text-center" colspan="2">QTY</th>
                        <th class="text-center" rowspan="2">Jenis</th>
                    </tr>
                    <tr>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Um 1</th>
                        <th class="text-center">Um 2</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- /Detail List Item -->
    <?php ActiveForm::end(); ?>
</div>