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
                    <?= $form->field($temp, 'no_spk')->widget(Select2::classname(), [
                            'data' => $spk,
                            'options' => [
                                'placeholder' => 'No. SPK',
                                'class' => 'select2',
                            ],
                        ]) ?>
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
            <h4>Form Add Bahan</h4>
            <hr>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30">
            <!-- Material -->
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Pilih Bahan:</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                    <?= $form->field($temp, 'item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-temp' => true])->label(false) ?>
                    <?= $form->field($temp, 'item_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                    <?= $form->field($temp, 'id')->hiddenInput(['data-temp' => true])->label(false) ?>
                    <?= $form->field($temp, 'urutan')->hiddenInput(['data-temp' => true])->label(false) ?>
                </div>
            </div>
            <!-- QTY -->
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>QTY Order:</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding-left-0 padding-right-0">
                        <?= $form->field($temp, 'qty_order_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'readonly' => true,
                                    'data-temp' => true
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 padding-left-0 padding-right-0">
                        <?= $form->field($temp, 'um_1')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding-left-0 padding-right-0">
                        <?= $form->field($temp, 'qty_order_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'readonly' => true,
                                    'maxlength' => 3,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 padding-left-0 padding-right-0">
                        <?= $form->field($temp, 'um_2')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-temp' => true])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <button class="btn btn-success margin-top-20" data-button="create_temp">
                    <i class="fontello icon-plus"></i>
                    <span>Tambah Data Detail</span>
                </button>
            </div>
        </div>
        <!-- /Form Input Detail -->
        <!-- Detail List Item -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30">
            <h4>Detail Bahan</h4>
            <hr>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10" data-table="detail">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">No.</th>
                        <th class="text-center" colspan="2">Item</th>
                        <th class="text-center" colspan="2">QTY</th>
                        <th class="text-center" rowspan="2">Jenis</th>
                        <th class="text-center" rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Um 1</th>
                        <th class="text-center">Um 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center text-danger" colspan="10">Data is empty</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /Detail List Item -->
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="form-group text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
function load_item()
{
    $.ajax({
        url: "<?=Url::to(['request-order/list-item'])?>",
		type: "GET",
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
        url: "<?=Url::to(['request-order/search'])?>",
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
				title: 'List Data Material',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){
		}
    });
}

function select_item(code)
{
    $.ajax({
        url: "<?=Url::to(['request-order/item'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            if(o.length !=0){
                $.each(o, function(index, value){
                    $("#temprequestorderitem-"+index).val(value);
                });
                for(var a=1;a<=o.composite;a++){
                    $("#temprequestorderitem-qty_order_"+a).attr("readonly", false);
                }
            }else{
                notification.open("danger", "Pricelist tidak ditemukan / belum aktif. Silakan isi pricelist / aktifkan pricelist yang akan digunakan di menu Material Item.");
                temp.destroy();
            }
        },
        complete: function(){
            popup.close();
        }
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['request-order/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-table=\"detail\"] > tbody").html(o.model);
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
            $.each(o, function(index, value){
                $("#temprequestorderitem-"+index).val(value);
            });
            if(o.um_1 != "") $("#temprequestorderitem-qty_order_1").attr("readonly", false);
            if(o.um_2 != "") $("#temprequestorderitem-qty_order_2").attr("readonly", false);
        },
        complete: function(){
            temp.init();
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
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            init_temp();
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
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            init_temp();
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
        data: {
            id: id
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            loading.open("loading bars");
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            init_temp();
            popup.close();
        },
        complete: function(){
			loading.close();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("keydown","#temprequestorderitem-item_name")
    $("body").on("keydown","#temprequestorderitem-item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item();
        }
    });
    
    $("body").off("click","[data-id=\"popup\"] table > tbody tr").on("click","[data-id=\"popup\"] table > tbody tr", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_item(data.code);
    });

    $("body").off("input","#temprequestorderitem-qty_order_2");
    $("body").on("input","#temprequestorderitem-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

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
    
    $("body").off("click","[data-button=\"delete_temp\"]").on("click","[data-button=\"delete_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete_temporary",
			target: data.id,
		});
    });
    $("body").off("click","#delete_temporary").on("click","#delete_temporary", function(e){
        e.preventDefault();
        delete_temp($(this).attr("data-target"));
    });
});

$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp();
    <?php endif; ?>
});
</script>