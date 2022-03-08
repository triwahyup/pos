<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanApproval */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pengaturan-approval-form">
    <?php $form = ActiveForm::begin(['id' => 'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?php if(!$model->isNewRecord): ?>
                    <?= $form->field($model, 'code')->hiddenInput()->label(false) ?>
                <?php endif; ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type')->widget(Select2::classname(), [
                        'data' => [1 => 'User', 2 => 'Type User'],
                        'options' => ['placeholder' => 'Type User'],
                    ]) ?>
                <?= $form->field($model, 'approval')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => ['placeholder' => 'User yang approve'],
                    ]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <button class="btn btn-success" data-button="create_temp">
                <i class="fontello icon-plus"></i>
                <span>Tambah User Approval</span>
            </button>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30">
            <table class="table table-bordered table-custom" data-table="detail">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">User</th>
                        <th class="text-center">Type User</th>
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
function type_approval(type)
{
    $.ajax({
        url: "<?= Url::to(['approval/type-approval']) ?>",
        type: "GET",
        dataType: "text",
        data: {
            type: type
        },
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#pengaturanapproval-approval").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#pengaturanapproval-approval").append(opt);
            });
            $("#pengaturanapproval-approval").val(null);
        },
        complete: function(){}
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['approval/temp']) ?>",
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
            $("#pengaturanapproval-type").val("").trigger("change");
            $("#pengaturanapproval-approval").val("").trigger("change");
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['approval/create-temp']) ?>",
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
        url: "<?= Url::to(['approval/delete-temp']) ?>",
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
    $("body").off("change","#pengaturanapproval-type").on("change","#pengaturanapproval-type", function(e){
        e.preventDefault();
        type_approval($(this).val());
    });

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        if(!$("#pengaturanapproval-name").val() && !$("#pengaturanapproval-type").val() && !$("#pengaturanapproval-approval").val()){
            notification.open("danger", "Name or Type or User Approve tidak boleh kosong.", timeOut);
        }else{
            create_temp($(this));
        }
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