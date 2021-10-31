<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                <?= $form->field($model, 'code')->hiddenInput(['maxlength' => true])->label(false) ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type_order')->widget(Select2::classname(), [
                        'data' => [1=>'Produksi', 2=>'Jasa'],
                        'options' => [
                            'placeholder' => 'Pilih Type Order',
                            'class' => 'select2',
                        ],
                    ]) ?>
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 8]) ?>
            </div>
            <div class="col-lg-1 col-md-1 col-xs-12 padding-left-0"></div>
            <div class="col-lg-7 col-md-7 col-xs-12 padding-right-0">
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
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <fieldset class="fieldset-box padding-20">
                <legend>Detail Material</legend>
                <div class="form-container">
                    <div class="margin-top-20"></div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Pilih Material:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-temp' => true])->label(false) ?>
                            <?= $form->field($temp, 'item_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Min Order:</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'qty_order_1')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-temp' => true,
                                        'readonly' => true,
                                        'placeholder' => 'RIM',
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
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
                                    'placeholder' => 'LB',
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="margin-top-20"></div>
                        <?= $form->field($temp, 'id')->hiddenInput(['data-temp' => true])->label(false) ?>
                        <?= $form->field($temp, 'order_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Potong (PxL):</label>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'panjang')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right',
                                                'data-temp' => true,
                                                'placeholder' => 'Panjang'
                                            ]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'lebar')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right',
                                                'data-temp' => true, 
                                                'placeholder' => 'Lebar'
                                            ]
                                        ])->label(false) ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Total Warna:</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'total_warna')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right', 
                                                'data-temp' => true,
                                                'placeholder' => 'Total Warna'
                                            ]
                                        ])->label(false) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Total Potong / Objek:</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'total_potong')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right',
                                                'data-temp' => true, 
                                                'placeholder' => 'Potong'
                                            ]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'total_objek')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right',
                                                'data-temp' => true, 
                                                'placeholder' => 'Objek'
                                            ]
                                        ])->label(false) ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Lb. Ikat / Satuan:</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'lembar_ikat')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right',
                                                'placeholder' => 'Lembar Ikat',
                                                'data-temp' => true
                                            ]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'lembar_ikat_type')->dropDownList([1=>'SAP', 2=>'IKAT', 3=>'DOS'], ['prompt'=>'Pilih Satuan Lembar Ikat', 'data-temp'=>true])->label(false) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                        <button class="btn btn-success margin-bottom-10 margin-top-10 margin-bottom-20" data-button="create_temp">
                            <i class="fontello icon-plus"></i>
                            <span>Tambah Data Detail</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0" data-render="detail"></div>
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
function load_item()
{
    $.ajax({
        url: "<?=Url::to(['order/list-item'])?>",
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
        url: "<?=Url::to(['order/search'])?>",
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
        complete: function(){}
    });
}

function select_item(code)
{
    $.ajax({
        url: "<?=Url::to(['order/item'])?>",
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
                $("#tempmasterorderdetail-"+index).val(value);
            });
            for(var a=1;a<=o.composite;a++){
                $("#tempmasterorderdetail-qty_order_"+a).attr("readonly", false);
            }
            $("#tempmasterorderdetail-qty_order_1").val(20);
        },
        complete: function(){
            popup.close();
        }
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['order/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail\"]").html(o.model);
            $("#masterorder-total_order").val(o.total_order);
            $("#masterorder-total_biaya").val(o.total_biaya);
            $("#masterorder-grand_total").val(o.grand_total);
            $("[id^=\"tempmasterorderdetail-qty_order_\"]").attr("readonly", true);
            
            checkbox.init();
            $.each(o.temps_produksi, function(index, value){
                $("#proses_"+value.biaya_produksi_code+'_'+value.item_code).prop("checked", true);
                $("#proses_"+value.biaya_produksi_code+'_'+value.item_code).next().find("i").removeClass("icon-ok").addClass("icon-ok");
                $("#proses_"+value.biaya_produksi_code+'_'+value.item_code).attr("data-id", value.id);
            });
        },
        complete: function(){
            temp.destroy();
        }
    });
}

function get_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['order/get-temp']) ?>",
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
                $("#tempmasterorderdetail-"+index).val(value);
            });
            if(o.um_1 != "") $("#tempmasterorderdetail-qty_order_1").attr("readonly", false);
            if(o.um_2 != "") $("#tempmasterorderdetail-qty_order_2").attr("readonly", false);
        },
        complete: function(){
            temp.init();
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['order/create-temp']) ?>",
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
        url: "<?= Url::to(['order/update-temp']) ?>",
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
        url: "<?= Url::to(['order/delete-temp']) ?>",
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

function create_temp_produksi(code, biaya, item)
{
    $.ajax({
        url: "<?= Url::to(['order/create-temp-produksi']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: {
            code: code,
            biaya: biaya,
            item: item,
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
        complete: function(){}
    });
}

function delete_temp_produksi(id)
{
    $.ajax({
        url: "<?= Url::to(['order/delete-temp-produksi']) ?>",
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
        },
        complete: function(){
			loading.close();
        }
    });
}

$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp();
    <?php endif; ?>
});

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("keydown","#tempmasterorderdetail-item_name")
    $("body").on("keydown","#tempmasterorderdetail-item_name", function(e){
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

    $("body").off("input","#tempmasterorderdetail-qty_order_2");
    $("body").on("input","#tempmasterorderdetail-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        if(!$("#masterorder-name").val() && !$("#masterorder-type_order").val()){
            notification.open("danger", "Nama dan Type Order tidak boleh kosong!", timeOut);
        }
        if(!$("#tempmasterorderdetail-qty_order_1").val() && !$("#tempmasterorderdetail-qty_order_2").val()){
            notification.open("danger", "QTY tidak boleh kosong!", timeOut);
        }
        
        var success = true,
            message = "";
        $.each($("[data-temp]:not(#tempmasterorderdetail-id):not(#tempmasterorderdetail-order_code):not([id^=\"tempmasterorderdetail-qty_order_\"])"), function(index, element){
            var a = $(element).attr("id").split("-"),
                b = a[1].replace("_", " ");
            if(!$(element).val()){
                success = false;
                message += parsing.toUpper(b, 2)+', ';
            }
        });
        
        if(success){
            create_temp($(this));
        }else{
            notification.open("danger", message +' tidak boleh kosong!', timeOut);
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

    $("body").off("click","[id^=\"proses_\"]").on("click","[id^=\"proses_\"]", function(e){
        var prop = $(this).prop("checked"), 
            id = $(this).attr("id").split("_")[1]+'_'+$(this).attr("id").split("_")[2];
        if(prop == true){
            console.log("Add Proses "+id);
            create_temp_produksi($("#code_"+id).val(), $("#biaya_"+id).val(), $("#item_"+id).val());
        }else{
            console.log("Delete Proses "+id);
            delete_temp_produksi($(this).attr("data-id"));
        }
    });
});
</script>