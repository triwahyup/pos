<?php
use app\models\User;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Log Mail';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-mail-index">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('log-mail[D]')):?>
        <div class="text-right">
            <button class="btn btn-default" data-button="delete">
                <i class="fontello icon-trash"></i>
                <span>Clear Data Log</span>
            </button>
        </div>
    <?php endif;?>
    <?php ActiveForm::begin(['id' => 'form']) ?>
        <div data-load="logs"></div>
        <div class="hidden">
            <input id="page" name="Filter[page]" type="hidden">
            <input id="limit" name="Filter[limit]" type="hidden">
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function load_data(tableId)
{
    $.ajax({
        url: "<?=Url::to(['mail/load-data'])?>",
		type: "POST",
		dataType: "text",
        data: $("#form").serialize(),
        beforeSend: function(){
            $("#"+tableId).tableLoader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-load=\"logs\"]").html(o.data);
        },
        complete: function(){
			$("#"+tableId).tableLoader("destroy");
		}
    });
}

function delete_logs()
{
    $.ajax({
        url: "<?= Url::to(['mail/delete']) ?>",
        type: "POST",
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
            popup.close();
            load_data("logs_mail");
        },
        complete: function(){
			loading.close();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("click","[data-page]").on("click","[data-page]", function(e) {
        e.preventDefault();

        var page = $(this).attr("data-page");
		$("#page").val(page);
        load_data("logs_mail");
        
        $("[data-load=\"logs\"] li").removeClass("active");
        $("[data-page=\""+page+"\"]:eq(0)").parent().removeClass("active").addClass("active");
    });

    $("body").off("click","[data-button=\"delete\"]").on("click","[data-button=\"delete\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus semua data log ?",
			selector: "delete",
			target: "",
		});
    });
    $("body").off("click","#delete").on("click","#delete", function(e){
        e.preventDefault();
        delete_logs();
    });
});
$(function(){
    load_data("logs_mail");
});
</script>