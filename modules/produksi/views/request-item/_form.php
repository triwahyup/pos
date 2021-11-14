<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkRequestItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-request-item-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">No. SPK:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($model, 'no_spk')->textInput(['placeholder'=>'Masukkan No. SPK ...', 'readonly' => (!$model->isNewRecord) ? true : false])->label(false) ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <button class="btn btn-default" data-button="search_spk">
                    <i class="fontello icon-search"></i>
                    <span>Search</span>
                </button>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <?= $form->field($detail, 'item_code')->hiddenInput()->label(false) ?>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">Material:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($detail, 'item_name')->textInput(['readonly'=>true, 'placeholder' => 'Material Name', 'value' => (!$model->isNewRecord) ? (isset($detail->item)) ? $detail->item->name : '' : ''])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">QTY Request:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($detail, 'qty_order_1')->widget(MaskedInput::className(), [
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                        'options' => [
                            'data-align' => 'text-right',
                            'placeholder' => 'RIM',
                            'value' => (!$model->isNewRecord) ? (!empty($detail->qty_order_1)) ? $detail->qty_order_1 : '' : '',
                        ]
                    ])->label(false) ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($detail, 'qty_order_2')->widget(MaskedInput::className(), [
                        'clientOptions' => [
                            'alias' =>  'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true,
                        ],
                        'options' => [
                            'data-align' => 'text-right',
                            'maxlength' => 3,
                            'placeholder' => 'LB',
                            'value' => (!$model->isNewRecord) ? (!empty($detail->qty_order_2)) ? $detail->qty_order_2 : '' : '',
                        ]
                    ])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">Keterangan:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 3])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="form-group text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function search_spk(el)
{
    var no_spk = $("#spkrequestitem-no_spk").val();
    $.ajax({
        url: "<?=Url::to(['request-item/search-spk'])?>",
        type: "POST",
        data: {
            no_spk: no_spk,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){
            el.loader("load");
        },
        success: function(data){
            $("[id^=\"spkrequestitemdetail-\"]").val(null);
            var o = $.parseJSON(data);
            if(o.success == true){
                $.each(o.model, function(index, value){
                    $("#spkrequestitemdetail-"+index).val(value);
                });
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

var timeOut=7000;
$(document).ready(function(){
    $("body").off("click","[data-button=\"search_spk\"]").on("click","[data-button=\"search_spk\"]", function(e){
        e.preventDefault();
        search_spk($(this));
    });

    $("body").off("input","#spkrequestitemdetail-qty_order_2");
    $("body").on("input","#spkrequestitemdetail-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });
});

$(function(){
    <?php if(!$model->isNewRecord): ?>
        $("[data-button=\"search_spk\"]").prop("disabled", true);
    <?php endif; ?>
});
</script>