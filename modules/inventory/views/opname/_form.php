<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryOpname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-opname-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <?= $form->field($model, 'code')->hiddenInput()->label(false) ?>
            <?= $form->field($temp, 'item_code')->hiddenInput(['data-temp' => true])->label(false) ?>
            <?= $form->field($temp, 'id')->hiddenInput(['data-temp' => true])->label(false) ?>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">Supplier:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($model, 'supplier_code')->widget(Select2::classname(), [
                        'data' => $supplier,
                        'options' => ['placeholder' => 'Supplier'],
                    ])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">Item Name:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">QTY Stock:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding-left-0 padding-right-0">
                    <?= $form->field($temp, 'qty_stock_1')->widget(MaskedInput::className(), [
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
                    <?= $form->field($temp, 'um_stock_1')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-temp' => true])->label(false) ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding-left-0 padding-right-0">
                    <?= $form->field($temp, 'qty_stock_2')->widget(MaskedInput::className(), [
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
                    <?= $form->field($temp, 'um_stock_2')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-temp' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">QTY Opname:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding-left-0 padding-right-0">
                    <?= $form->field($temp, 'qty_1')->widget(MaskedInput::className(), [
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
                    <?= $form->field($temp, 'qty_2')->widget(MaskedInput::className(), [
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
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label class="font-size-12 font-bold">Keterangan:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12">
                <?= $form->field($temp, 'keterangan')->textarea(['row' => 3, 'data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <button class="btn btn-success margin-bottom-20" data-button="create_temp">
                <i class="fontello icon-plus"></i>
                <span>Tambah Data Detail</span>
            </button>
        </div>
        <!-- DETAIL -->
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="margin-top-20"></div>
                    <table class="table table-bordered table-custom" data-table="detail">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item</th>
                                <th class="text-center" colspan="2">QTY Stock</th>
                                <th class="text-center" colspan="2">QTY Opname</th>
                                <th class="text-center">Selisih</th>
                                <th class="text-center">Balance</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center text-danger" colspan="15">Data is empty</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /DETAIL -->  
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
        url: "<?=Url::to(['opname/list-item'])?>",
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
        url: "<?=Url::to(['opname/search'])?>",
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
        url: "<?=Url::to(['opname/item'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o.item, function(index, value){
                $("#tempinventoryopnamedetail-"+index).val(value);
            });
            console.log(o);
            $("#tempinventoryopnamedetail-qty_stock_1").val(o.stock[0]);
            $("#tempinventoryopnamedetail-qty_stock_2").val(o.stock[1]);
            $("#tempinventoryopnamedetail-um_stock_1").val(o.item.um_1);
            $("#tempinventoryopnamedetail-um_stock_2").val(o.item.um_2);
            for(var a=1;a<=o.item.composite;a++){
                $("#tempinventoryopnamedetail-qty_"+a).attr("readonly", false);
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
        url: "<?= Url::to(['opname/temp']) ?>",
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
        url: "<?= Url::to(['opname/get-temp']) ?>",
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
                $("#tempinventoryopnamedetail-"+index).val(value);
            });
            if(o.um_1 != "") $("#tempinventoryopnamedetail-qty_order_1").attr("readonly", false);
            if(o.um_2 != "") $("#tempinventoryopnamedetail-qty_order_2").attr("readonly", false);
        },
        complete: function(){
            temp.init();
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['opname/create-temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: $("#form").serialize(),
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
        url: "<?= Url::to(['opname/update-temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: $("#form").serialize(),
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
        url: "<?= Url::to(['opname/delete-temp']) ?>",
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

var timeOut = 6000;
$(document).ready(function(){
    $("body").off("keydown","#tempinventoryopnamedetail-item_name")
    $("body").on("keydown","#tempinventoryopnamedetail-item_name", function(e){
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

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        create_temp($(this));
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