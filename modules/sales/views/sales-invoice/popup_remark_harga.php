<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'form_remark']); ?>
    <div class="hidden">
        <?= $form->field($item, 'no_invoice')->hiddenInput(['value' => $item->no_invoice, 'readonly' => true])->label(false) ?>
        <?= $form->field($item, 'type_invoice')->hiddenInput(['value' => $item->type_invoice, 'readonly' => true])->label(false) ?>
        <?= $form->field($item, 'urutan')->hiddenInput(['value' => $item->urutan, 'readonly' => true])->label(false) ?>
    </div>
    <?php if(!empty($item->proses_code)): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">(Rp) Biaya Produksi:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($item, 'harga_jual_1')->textInput(['readonly' => true, 'data-align' => 'text-right'])->label(false) ?>
            </div>
        </div>
    <?php else: ?>
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
    <?php endif; ?>
    <?php if(!empty($item->proses_code)): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">(Rp) Remark Biaya Produksi:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($item, 'new_harga_jual_1')->textInput(['autocomplete' => 'off', 'data-align' => 'text-right'])->label(false) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">(Rp) Remark Harga / Per RIM:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($item, 'new_harga_jual_1')->textInput(['autocomplete' => 'off', 'data-align' => 'text-right'])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">(Rp) Remark Harga / Per LB:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($item, 'new_harga_jual_2')->textInput(['autocomplete' => 'off', 'data-align'=>'text-right'])->label(false) ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
        <button class="btn btn-primary margin-bottom-20" data-button="update">
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