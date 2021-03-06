<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryBast */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-bast-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="hidden">
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                    'data' => $dataList['user'],
                    'options' => ['placeholder' => 'User'],
                    ]) ?>

                <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [
                        'placeholder' => 'dd-mm-yyyy',
                        'value' => date('d-m-Y'),
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-mm-yyyy',
                    ]]) ?>

                <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                    'data' => $dataList['bast'],
                    'options' => ['placeholder' => 'Type'],
                    ]) ?>
                <?= $form->field($model, 'keterangan')->textarea(['maxlength' => true]) ?>
            </div>
        </div>
        <!-- DETAIL -->
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-30"></div>
            <h4>Detail Barang</h4>
            <hr>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="margin-top-20"></div>
            <div class="hidden">
                <?= $form->field($temp, 'id')->hiddenInput(['data-temp' => true])->label(false) ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label>Pilih Barang:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'name')->textInput(['placeholder' => 'Pilih barang tekan F4', 'data-temp' => true])->label(false) ?>
                <?= $form->field($temp, 'barang_code')->hiddenInput(['data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label>Qty / Satuan:</label>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'qty')->widget(MaskedInput::className(), [
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'groupSeparator' => ',',
                            'autoGroup' => true
                        ],
                        'options' => [
                            'data-align' => 'text-right',
                            'data-temp' => true,
                            'placeholder' => 'QTY',
                        ]
                    ])->label(false) ?>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'um')->textInput(['placeholder' => 'Satuan', 'data-temp' => true, 'data-align' => 'text-right', 'readonly' => true])->label(false) ?>
                <?= $form->field($temp, 'satuan_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                <?= $form->field($temp, 'supplier_code')->hiddenInput(['data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label>Kode Serial:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'kode_sn')->textInput(['placeholder' => 'Kode Serial', 'data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label>Kode Unik:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'kode_unik')->textInput(['placeholder' => 'Kode Unik', 'data-temp' => true])->label(false) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <span class="font-size-10 text-primary">Kode unik berisi kode informasi tambahan. Bisa diisi dengan No / ID inventaris, dll.</span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <label>Keterangan:</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'keterangan')->textarea(['data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 text-right">
            <button class="btn btn-success margin-bottom-20" data-button="create_temp">
                <i class="fontello icon-plus"></i>
                <span>Tambah Data Detail</span>
            </button>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <table class="table table-bordered table-custom" data-table="detail">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">Satuan</th>
                        <th class="text-center">Kode Serial</th>
                        <th class="text-center">Kode Unik</th>
                        <th class="text-center">Supplier</th>
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
function load_barang(type_barang)
{
    $.ajax({
        url: "<?=Url::to(['bast/list-barang'])?>",
		type: "POST",
        data: {
            type_barang: type_barang
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                $("[data-popup=\"popup\"]").html(o.data);
                $("[data-popup=\"popup\"]").popup("open", {
                    container: "popup",
                    title: 'List Data Barang',
                    styleOptions: {
                        width: 600
                    }
                });
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){}
    });
}

function search_barang(code)
{
    $.ajax({
        url: "<?=Url::to(['bast/search'])?>",
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
				title: 'List Data Barang',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){
		}
    });
}

function select_barang(code)
{
    $.ajax({
        url: "<?=Url::to(['bast/barang'])?>",
		type: "POST",
        data: {
            code: code
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#tempinventorybastdetail-"+index).val(value);
            });
        },
        complete: function(){
            popup.close();
        }
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['bast/temp']) ?>",
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
        url: "<?= Url::to(['bast/get-temp']) ?>",
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
                $("#tempinventorybastdetail-"+index).val(value);
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
        url: "<?= Url::to(['bast/create-temp']) ?>",
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
        url: "<?= Url::to(['bast/update-temp']) ?>",
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
        url: "<?= Url::to(['bast/delete-temp']) ?>",
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
    $("body").off("keydown","#tempinventorybastdetail-name")
    $("body").on("keydown","#tempinventorybastdetail-name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_barang($("#inventorybast-type_code").val());
        }
    });
    
    $("body").off("click","[data-id=\"popup\"] table > tbody tr")
    $("body").on("click","[data-id=\"popup\"] table > tbody tr", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_barang(data.code);
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