<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-internal-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>No. SPK</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'no_spk')->textInput(['readonly' => true, 'value' => $model->no_spk])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>Tgl. SPK</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'tgl_spk')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'yyyy-mm-dd',
                                'value' => date('Y-m-d'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>No. SO</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'no_so')->textInput(['readonly' => true])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>Bahan</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($detail, 'item_code')->textInput(['readonly' => true])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>Potong (P x L)</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 padding-right-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'panjang')->textInput()->label(false) ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <span>x</span>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'lebar')->textInput()->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>Potong (P x L)</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 padding-right-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'panjang')->textInput()->label(false) ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <span>x</span>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'lebar')->textInput()->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>Potong (P x L)</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 padding-right-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'panjang')->textInput()->label(false) ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <span>x</span>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'lebar')->textInput()->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <label>Potong (P x L)</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 padding-right-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'panjang')->textInput()->label(false) ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <span>x</span>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($detail, 'lebar')->textInput()->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>