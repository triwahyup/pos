<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
            <!-- HEADER -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Job</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- No. SO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. SO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_so', [
                                'template' => '{input}
                                    <span class="text-muted">'.$sorder->name .' - '.$sorder->customer->name.'</span>
                                    {error}{hint}'
                            ])->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
                <!-- No. SPK -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. SPK:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_spk')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
                <!-- No. Request -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. Request:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_request')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Tgl. SO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl. Request:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'tgl_request')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'dd-mm-yyyy',
                                'value' => (!$model->isNewRecord) ? date('d-m-Y', strtotime($model->tgl_request)) : date('d-m-Y'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]])->label(false) ?>
                    </div>
                </div>
                <!-- Keterangan -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Keterangan:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 2])->label(false) ?>
                    </div>
                </div>
            </div>
            <!-- /HEADER -->
            <!-- ITEM -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Item</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Item Name -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Material:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($temp, 'item_name')->textInput([
                            'placeholder' => 'Pilih material tekan F4', 'data-type'=>'item', 'aria-required' => true, 'data-temp' => true])->label(false) ?>
                        <?= $form->field($temp, 'id')->hiddenInput(['data-temp' => true])->label(false) ?>
                        <?= $form->field($temp, 'item_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                        <?= $form->field($temp, 'supplier_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <!-- Type Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Type QTY:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($temp, 'type_qty')->widget(Select2::classname(), [
                                'data' => [1=>'RIM', 2=>'LEMBAR'],
                                'options' => [
                                    'aria-required' => true,
                                    'placeholder' => 'Pilih Type QTY',
                                    'class' => 'select2',
                                    'data-temp' => true,
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Qty Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Qty:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($temp, 'qty_order_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'aria-required' => true,
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                        <label id="satuan_qty_temp" class="font-size-14 margin-left-5 margin-top-5 hidden">RIM</label>
                    </div>
                </div>
            </div>
            <!-- Button Action -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-10 text-right">
                <button class="btn btn-success margin-bottom-20" data-button="create_temp" data-type="item">
                    <i class="fontello icon-plus"></i>
                    <span>Tambah Data Detail</span>
                </button>
                <button class="btn btn-success margin-bottom-20 hidden" data-button="change_temp">
                    <i class="fontello icon-plus"></i>
                    <span>Update Data Detail</span>
                </button>
                <button class="btn btn-danger margin-bottom-20 margin-left-5 hidden" data-button="cancel">
                    <i class="fontello icon-cancel"></i>
                    <span>Cancel</span>
                </button>
            </div>
            <!-- /ITEM -->
            <!-- VIEW DETAIL TEMP -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div render="detail" class="form-container padding-bottom-5">
                    <!-- detail item -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <h4>Detail Material</h4>
                        <hr />
                    </div>
                    <div data-render="detail-item">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger" colspan="8">Data masih kosong.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail item -->
                    <!-- form bahan pembantu -->
                    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                        <h4>Detail Bahan Pembantu</h4>
                        <hr />
                    </div>
                    <!-- Item Bahan -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>Item Bahan:</strong>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'bahan_item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-type'=>'bahan', 'data-temp'=>true])->label(false) ?>
                            <?= $form->field($temp, 'bahan_item_code')->hiddenInput(['data-temp'=>true])->label(false) ?>
                            <?= $form->field($temp, 'bahan_supplier_code')->hiddenInput(['data-temp'=>true])->label(false) ?>
                        </div>
                    </div>
                    <!-- QTY Bahan -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>Qty:</strong>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'bahan_qty')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-temp' => true,
                                        'readonly' => true,
                                        'placeholder' => 'KG',
                                    ]
                                ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                        <button class="btn btn-success" data-button="create_temp_bahan">
                            <i class="fontello icon-plus"></i>
                            <span>Tambah Data Bahan</span>
                        </button>
                    </div>
                    <!-- /form bahan pembantu -->
                    <!-- detail bahan pembantu -->
                    <div data-render="detail-bahan">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger" colspan="10">Data masih kosong.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail bahan pembantu -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="form-group text-right">
                            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /VIEW DETAIL TEMP -->
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
function load_item(type)
{
    $.ajax({
        url: "<?=Url::to(['request-order/list-item'])?>",
		type: "GET",
        data: {
            type: type,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material',
				styleOptions: {
					width: 800
				}
			});
        },
        complete: function(){}
    });
}

function search_item(code, supplier, type)
{
    $.ajax({
        url: "<?=Url::to(['request-order/search-item'])?>",
		type: "POST",
        data: {
            code: code,
            supplier: supplier,
            type: type,
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
				title: 'List Data Material',
				styleOptions: {
					width: 800
				}
			});
        },
        complete: function(){}
    });
}

function select_item(code, supplier, type)
{
    $.ajax({
        url: "<?=Url::to(['request-order/select-item'])?>",
		type: "POST",
        data: {
            code: code,
            supplier: supplier,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){
            if(type != 'item'){
                $("[id^=\"temprequestorderitem-bahan_\"]").val(null);
            }
        },
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                if(type == 'item'){
                    $("#temprequestorderitem-"+index).val(value);
                }else{
                    $("#temprequestorderitem-bahan_"+index).val(value);
                }
            });
            if(type == 'bahan'){
                $("#temprequestorderitem-bahan_qty").attr("readonly", false);
            }
        },
        complete: function(){
            popup.close();
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['request-order/create-temp']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            init_temp_item();
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function update_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['request-order/update-temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: $("#form").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            init_temp_item();
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function delete_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['request-order/delete-temp']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            loading.open("loading bars");
        },
        data: {
            id: id
        },
        success: function(data){
            var o = $.parseJSON(data);
            init_temp_item();
            init_temp_bahan();
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            popup.close();
        },
        complete: function(){
			loading.close();
        }
    });
}

function init_temp_item()
{
    $.ajax({
        url: "<?= Url::to(['request-order/temp-item']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-item\"] table > tbody").html(o.model);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

function get_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['request-order/get-temp']) ?>",
        type: "GET",
        dataType: "text",
        data: {
            id: id
        },
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            if(o.qty_order_1 !== null){
                $("#temprequestorderitem-type_qty").val(1).trigger("change");
            }
            if(o.qty_order_2 !== null){
                $("#temprequestorderitem-type_qty").val(2).trigger("change");
            }
            $.each(o, function(index, value){
                $("#temprequestorderitem-"+index).val(value);
            });
        },
        complete: function(){
            temp.init();
        }
    });
}

function create_temp_bahan(el)
{
    $.ajax({
        url: "<?= Url::to(['request-order/create-temp']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            init_temp_bahan();
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function init_temp_bahan()
{
    $.ajax({
        url: "<?= Url::to(['request-order/temp-bahan']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-bahan\"] table > tbody").html(o.model);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#temprequestorderitem-type_qty").on("change","#temprequestorderitem-type_qty", function(e){
        e.preventDefault();
        $("[id^=\"temprequestorderitem-qty_order_\"]").val(null);
        $("#satuan_qty_temp").removeClass("hidden");
        if($(this).val() == 1){
            $("#satuan_qty_temp").text("RIM");
            $("#temprequestorderitem-qty_order_2").attr("id", "temprequestorderitem-qty_order_1");
            $("[name=\"TempRequestOrderItem[qty_order_2]\"]").attr("name", "TempRequestOrderItem[qty_order_1]");
        }else if($(this).val() == 2){
            $("#satuan_qty_temp").text("LEMBAR");
            $("#temprequestorderitem-qty_order_1").attr("id", "temprequestorderitem-qty_order_2");
            $("[name=\"TempRequestOrderItem[qty_order_1]\"]").attr("name", "TempRequestOrderItem[qty_order_2]");
        }else{
            $("#satuan_qty_temp").removeClass("hidden").addClass("hidden");
        }
    });

    /** LOAD ITEM MATERIAL & BAHAN */
    $("body").off("keydown","#temprequestorderitem-item_name")
    $("body").on("keydown","#temprequestorderitem-item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("keydown","#temprequestorderitem-bahan_item_name")
    $("body").on("keydown","#temprequestorderitem-bahan_item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("click","table[data-table=\"master_item_material\"] > tbody tr[data-code]");
    $("body").on("click","table[data-table=\"master_item_material\"] > tbody tr[data-code]", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_item(data.code, data.supplier, data.type);
    });
    /** END LOAD ITEM MATERIAL & BAHAN */

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        success = true;
        $.each($("[aria-required]:not([readonly])"), function(index, element){
            var a = $(element).attr("id").split("-"),
                b = a[1].replace("_", " ");
            if(!$(element).val()){
                success = false;
                errorMsg = parsing.toUpper(b, 2) +' cannot be blank.';
                if($(element).parent().hasClass("input-container")){
                    $(element).parent(".input-container").parent().removeClass("has-error").addClass("has-error");
                    $(element).parent(".input-container").siblings("[class=\"help-block\"]").text(errorMsg);
                }else{
                    $(element).parent().removeClass("has-error").addClass("has-error");
                    $(element).siblings("[class=\"help-block\"]").text(errorMsg);
                }
            }
        });
        if(success){
            create_temp($(this));
        }
    });
    
    $("body").off("click","[data-button=\"update_temp\"]").on("click","[data-button=\"update_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        get_temp(data.id);
    });
    $("body").off("click","[data-button=\"change_temp\"]").on("click","[data-button=\"change_temp\"]", function(e){
        e.preventDefault();
        update_temp($(this));
    });
    
    $("body").off("click","[data-button=\"delete_temp\"]");
    $("body").on("click","[data-button=\"delete_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete_temp",
			target: data.id,
		});
    });
    $("body").off("click","#delete_temp").on("click","#delete_temp", function(e){
        e.preventDefault();
        delete_temp($(this).attr("data-target"));
    });

    $("body").off("click","[data-button=\"create_temp_bahan\"]");
    $("body").on("click","[data-button=\"create_temp_bahan\"]", function(e){
        e.preventDefault();
        create_temp_bahan($(this));
    });
});
$(function(){
    init_temp_item();
    init_temp_bahan();
});
</script>