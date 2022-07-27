<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'form_biaya_lain']); ?>
    <div class="hidden">
        <?= $form->field($item, 'no_invoice')->hiddenInput(['value' => $item->no_invoice, 'readonly' => true])->label(false) ?>
        <?= $form->field($item, 'type_invoice')->hiddenInput(['value' => 3, 'readonly' => true])->label(false) ?>
        <?= $form->field($item, 'urutan')->hiddenInput(['value' => ($update) ? $item->urutan : '', 'readonly' => true])->label(false) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">Type Ongkos:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'type_ongkos')->dropDownList($typeOngkos, ['prompt'=>'Biaya Lainnya...'])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">Kode Refrensi:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'unique_code')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">(Rp) Biaya:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'harga_jual_1')->textInput(['autocomplete' => 'off', 'data-align' => 'text-right', 'value' => ($update) ? $item->harga_jual_1 : 0])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
        <button class="btn btn-primary margin-bottom-20" data-button="create_biaya">
            <i class="fontello icon-floppy"></i>
            <span>Simpan</span>
        </button>
    </div>
<?php ActiveForm::end(); ?>
<script>
$(function(){
    $("#salesinvoiceitem-harga_jual_1").mask("000,000,000,000,000", {reverse: true});
});
</script>