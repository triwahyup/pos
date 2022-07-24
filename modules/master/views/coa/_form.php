<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterCoa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-coa-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <h6 class="font-size-14">Header</h6>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">Code:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'readonly' => ($model->isNewRecord) ? false : true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">Name:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <h6 class="font-size-14 margin-top-20">Detail</h6>
            <hr />
        </div>
        <div class="hidden">
            <?= $form->field($temp, 'id')->hiddenInput(['data-temp' => true])->label(false) ?>
            <?= $form->field($temp, 'code')->hiddenInput(['data-temp' => true])->label(false) ?>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">Detail Code:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'urutan')->textInput(['maxlength' => 4, 'aria-required' => true, 'data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">Name:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($temp, 'name')->textInput(['maxlength' => true, 'aria-required' => true, 'data-temp' => true])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <button class="btn btn-success" data-button="create_temp">
                <i class="fontello icon-plus"></i>
                <span>Tambah Data Coa</span>
            </button>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 margin-top-30">
            <table class="table table-bordered table-custom" data-table="detail">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center text-danger" colspan="5">Data is empty</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function get_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['coa/get-temp']) ?>",
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
                $("#tempmastercoadetail-"+index).val(value);
            });
        },
        complete: function(){
            temp.init();
        }
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['coa/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            $("[data-table=\"detail\"] > tbody").html(data);
        },
        complete: function(){
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['coa/create-temp']) ?>",
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
        url: "<?= Url::to(['coa/update-temp']) ?>",
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
        url: "<?= Url::to(['coa/delete-temp']) ?>",
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

var timeOut = 3000;
$(document).ready(function(){
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