<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'form_remark']); ?>
    <div class="hidden">
        <?= $form->field($item, 'no_invoice')->hiddenInput(['value' => $item->no_invoice, 'readonly' => true])->label(false) ?>
        <?= $form->field($item, 'no_invoice')->hiddenInput(['value' => $item->type_invoice, 'readonly' => true])->label(false) ?>
        <?= $form->field($item, 'no_invoice')->hiddenInput(['value' => $item->urutan, 'readonly' => true])->label(false) ?>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">(Rp) Harga / Per RIM:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'harga_jual_1')->textInput(['data-align'=>'text-right', 'value' => number_format($item->harga_jual_1), 'readonly' => true])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">(Rp) Harga / Per LB:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'harga_jual_2')->textInput(['data-align'=>'text-right', 'value' => number_format($item->harga_jual_2), 'readonly' => true])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">(Rp) Remark Harga / Per RIM:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'new_harga_jual_1')->textInput(['data-align' => 'text-right'])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label class="font-size-12">(Rp) Remark Harga / Per LB:</label>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
            <?= $form->field($item, 'new_harga_jual_2')->textInput(['data-align'=>'text-right'])->label(false) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
        <button class="btn btn-primary margin-bottom-20" data-button="update_invoice">
            <i class="fontello icon-floppy"></i>
            <span>Update</span>
        </button>
    </div>
<?php ActiveForm::end(); ?>
<script>
$(function(){
    $("#salesinvoiceitem-new_harga_jual_1").mask("000,000,000,000,000", {reverse: true});
    $("#salesinvoiceitem-new_harga_jual_2").mask("000,000,000,000,000", {reverse: true});
});
</script>