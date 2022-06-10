<?php
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
?>

<div class="popup-form">
	<div class="popup-form-header">
		<h5>Input Hasil Produksi</h5>
		<a href="javascript:void(0)" class="popup-remove" id="btn-remove">
			<i class="fontello dark-blue icon-cancel-2"></i>
		</a>
	</div>
	<div class="popup-form-body">
		<?php $form = ActiveForm::begin(['id'=>'form-hasil']); ?>
            <div class="hidden">
                <?= $form->field($model, 'no_spk')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'item_code')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'proses_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'urutan')->hiddenInput()->label(false) ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>QTY Proses:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'qty_proses')->textInput(['data-align' => 'text-right', 'readonly'=>true])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>QTY Hasil:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'qty_hasil')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-align' => 'text-right',
                            ]
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>QTY Rusak:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'qty_rusak')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-align' => 'text-right',
                            ]
                        ])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Keterangan:</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($model, 'keterangan')->textarea()->label(false) ?>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-success" data-button="update">
					<i class="fontello icon-ok"></i>
					<span>INPUT HASIL</span>
				</button>
            </div>
        <?php ActiveForm::end(); ?>
	</div>
</div>