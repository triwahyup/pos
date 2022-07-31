<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-barang-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                                'data' => $dataList['barang'],
                                'options' => [
                                    'placeholder' => 'Type Barang',
                                    'readonly' => (!$model->isNewRecord) ? true : false,
                                ],
                            ]) ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'satuan_code')->widget(Select2::classname(), [
                                'data' => $dataList['satuan_barang'],
                                'options' => ['placeholder' => 'Satuan'],
                            ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'code')->textInput(['maxlength' => true , 'readonly' => true]) ?>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 4]) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Price List -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h4>Price List</h4>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                <label>Name:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'name')->textInput([
                    'readonly' => true, 'placeholder' => 'Nama Pricelist', 'data-temp' => true, 'aria-required' => true])->label(false) ?>
                <?= $form->field($temp, 'id')->hiddenInput(['readonly' => true, 'data-temp' => true])->label(false) ?>
                <?= $form->field($temp, 'barang_code')->hiddenInput(['readonly' => true, 'data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                <label>Supplier:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'supplier_code')->widget(Select2::classname(), [
                        'data' => $dataList['supplier_barang'],
                        'options' => [
                            'placeholder' => 'Pilih Supplier',
                            'class' => 'select2',
                            'data-temp' => true,
                            'aria-required' => true,
                        ],
                    ])->label(false) ?>
            </div>
        </div>
        <!-- HARGA 1 -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                <label>UM 1:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'um_1')->textInput([
                    'readonly' => true, 'placeholder' => 'UM 1', 'data-temp' => (!$model->isNewRecord) ? false : true, 'aria-required' => true])->label(false) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'harga_beli_1')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                    'options' => [
                        'data-temp' => true,
                        'data-align' => 'text-right',
                        'data-name' => 'iconbox',
                        'data-icons' => 'rupiah',
                        'placeholder' => 'Harga Beli 1',
                        'readonly' => true,
                        'aria-required' => true
                    ]
                ])->label(false) ?>
            </div>
        </div>
        <!-- /HARGA 1 -->
        <!-- HARGA 2 -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                <label>UM 2:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'um_2')->textInput([
                    'readonly' => true, 'placeholder' => 'UM 2', 'data-temp' => (!$model->isNewRecord) ? false : true, 'aria-required' => true])->label(false) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'harga_beli_2')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                    'options' => [
                        'data-temp' => true,
                        'data-align' => 'text-right',
                        'data-name' => 'iconbox',
                        'data-icons' => 'rupiah',
                        'placeholder' => 'Harga Beli 2',
                        'readonly' => true,
                        'aria-required' => true
                    ]
                ])->label(false) ?>
            </div>
        </div>
        <!-- /HARGA 2 -->
        <div class="col-lg-12 col-md-12 col-xs-12 text-right">
            <button class="btn btn-success margin-bottom-10 margin-top-10 margin-bottom-20" data-button="create_temp">
                <i class="fontello icon-plus"></i>
                <span>Tambah Pricelist</span>
            </button>
        </div>
        <!-- /Price List -->
        <!-- List Data Price List -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom" data-table="detail">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center" colspan="2">Harga Beli (Rp)</th>
                        <th class="text-center">Status Pricelist</th>
                        <th class="text-center"></th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center text-danger" colspan="10">Data is empty</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /List Data Price List -->
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="form-group text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function generateCode(type)
{
    $.ajax({
        url: "<?=Url::to(['barang/generate-code']) ?>",
        type: "GET",
        data: {
            type: type
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[id^=\"masterbarang-\"]:not(#masterbarang-type_code)").val(null);
            $("#masterbarang-code").val(o);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function um(code)
{
    $.ajax({
        url: "<?=Url::to(['barang/um']) ?>",
        type: "GET",
        data: {
            code: code
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[id^=\"tempmasterbarangpricelist-harga_beli_\"]").attr("readonly", true);
            $("#tempmasterbarangpricelist-um_1").val(o.um_1);
            $("#tempmasterbarangpricelist-um_2").val(o.um_2);
            for(var a=1;a<=o.composite;a++){
                $("#tempmasterbarangpricelist-harga_beli_"+a).attr("readonly", false);
            }
            $("#tempmasterbarangpricelist-name").attr("readonly", false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['barang/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-table=\"detail\"] > tbody").html(o.model);
            $.each($("[id^=\"status_active_\"]"), function(index, element){
                var el = $(element).val();
                if(el == 1){
                    $(element).prop("checked", true);
                }else{
                    $(element).prop("checked", false);
                }
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
        url: "<?= Url::to(['barang/get-temp']) ?>",
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
                $("#tempmasterbarangpricelist-"+index).val(value).trigger("change");
            });
        },
        complete: function(){
            temp.init();
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['barang/create-temp']) ?>",
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
        url: "<?= Url::to(['barang/update-temp']) ?>",
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
        url: "<?= Url::to(['barang/delete-temp']) ?>",
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

function status_active(id)
{
    $.ajax({
        url: "<?= Url::to(['barang/status-active']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: {
            id: id
        },
        success: function(data){
            init_temp();
        },
        complete: function(){}
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#masterbarang-type_code")
    $("body").on("change","#masterbarang-type_code", function(e){
        e.preventDefault();
        generateCode($(this).val());
    });

    $("body").off("change","#masterbarang-satuan_code")
    $("body").on("change","#masterbarang-satuan_code", function(e){
        e.preventDefault();
        um($(this).val());
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

    $("body").off("click","[id^=\"status_active_\"]").on("click","[id^=\"status_active_\"]", function(e){
        var data = $(this).data();
        status_active(data.id);
    });
});

$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp();
        um($("#masterbarang-satuan_code").val());
    <?php endif; ?>
});
</script>