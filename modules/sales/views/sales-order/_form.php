<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'customer_code')->widget(Select2::classname(), [
                            'data' => $customer,
                            'options' => [
                                'placeholder' => 'Pilih Customer',
                                'class' => 'select2',
                            ],
                        ]) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_so')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'yyyy-mm-dd',
                            'value' => date('Y-m-d'),
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_po')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_po')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'yyyy-mm-dd',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'ekspedisi_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'biaya_pengiriman')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' => 'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                            ]
                        ]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'up_produksi')->dropDownList(['5' => '5%', '10' => '10%'], ['prompt'=>'Produksi Up (%)']) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'ppn')->textInput() ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-10 col-md-10 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                <div class="margin-top-20"></div>
                <div class="board-container">
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                            <p class="title">Total Order Material</p>
                            <?= $form->field($model, 'total_order')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                            <p class="title">Total Biaya Produksi</p>
                            <?= $form->field($model, 'total_biaya')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="margin-top-20"></div>
                        <p class="title">Grand Total</p>
                        <?= $form->field($model, 'grand_total')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20 padding-left-0">
            <div class="margin-top-20"></div>
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($model, 'nama_order')->textInput(['placeholder' => 'Pilih data job tekan F4', 'value' => (!$model->isNewRecord) ? $model->order->name : '']) ?>
                <?= $form->field($model, 'order_code')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'type_order')->hiddenInput()->label(false) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($model, 'outsource_code')->widget(Select2::classname(), [
                        'data' => $outsourcing,
                        'options' => [
                            'placeholder' => 'Pilih Jasa',
                            'class' => 'select2',
                            'readonly' => true,
                        ],
                    ]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <fieldset class="fieldset-box padding-20">
                <legend>Detail Material</legend>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0" data-render="detail">
                    <p class="text-danger">Data masih kosong.</p>
                </div>
            </fieldset>
            <div class="margin-bottom-20"></div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="form-group text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
function load_biaya_produksi(el)
{
    data = el.data();
    $.ajax({
        url: "<?=Url::to(['sales-order/list-biaya'])?>",
		type: "GET",
        data: {
            no_so: data.so,
            order_code: data.order,
            item_code: data.item,
            urutan: data.urutan,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            $(".popup").remove();
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Biaya Produksi',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function load_data_order(code)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/load-order']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: {
            code: code,
        },
        success: function(data){
            init_temp();
        },
        complete: function(){}
    });
}

function load_list_order()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/list-order']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Job (Order)',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function search_item(code)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/search'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            popup.close();
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Job (Order)',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function select_order(code)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/select-order'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#salesorder-"+index).val(value);
            });
            if(o.type_order == 1){
                $("#salesorder-outsource_code").attr("readonly", true);
            }else{
                $("#salesorder-outsource_code").attr("readonly", false);
            }
            load_data_order(code);
        },
        complete: function(){
            popup.close();
        }
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail\"]").html(o.model);
            $("#salesorder-total_order").val(o.total_order);
            $("#salesorder-total_biaya").val(o.total_biaya);
            $("#salesorder-grand_total").val(o.grand_total);
        },
        complete: function(){}
    });
}

function create_temp_produksi()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/create-temp-produksi']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: $("#form_biaya").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            if(!o.success == true){
                notification.open("danger", o.message, timeOut);
            }
            init_temp();
        },
        complete: function(){
            popup.close();
        }
    });
}

function update_harga(el)
{
    data = el.data();
    harga_jual_1 = $("#price1_"+data.id).val();
    harga_jual_2 = $("#price2_"+data.id).val();
    $.ajax({
        url: "<?= Url::to(['sales-order/update-harga']) ?>",
        type: "POST",
        data: {
            id: data.id,
            harga_jual_1: harga_jual_1,
            harga_jual_2: harga_jual_2,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        success: function(data){
            init_temp();
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("click","#list_biaya_produksi").on("click","#list_biaya_produksi", function(e){
        e.preventDefault();
        load_biaya_produksi($(this));
    });

    $("body").off("keydown","#salesorder-nama_order").on("keydown","#salesorder-nama_order", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_list_order();
        }
    });

    $("body").off("click","[data-id=\"popup\"] table > tbody tr[data-code]")
    $("body").on("click","[data-id=\"popup\"] table > tbody tr[data-code]", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_order(data.code);
    });

    $("body").off("click","[data-button=\"create_biaya_produksi\"]");
    $("body").on("click","[data-button=\"create_biaya_produksi\"]", function(e){
        e.preventDefault();
        create_temp_produksi();
    });

    $("body").off("click","[data-button=\"update_harga\"]").on("click","[data-button=\"update_harga\"]", function(e){
        e.preventDefault();
        update_harga($(this));
    });
});
$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp();
    <?php endif; ?>
});
</script>