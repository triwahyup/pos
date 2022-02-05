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
        <div class="form-container no-background">
            <!-- list detail -->
            <div class="col-lg-12 col-md-12 col-xs-12" data-render="detail">
                <table class="table table-bordered table-custom margin-top-10">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">P x L</th>
                            <th class="text-center">Total Potong</th>
                            <th class="text-center">Total Objek</th>
                            <th class="text-center">Total Warna</th>
                            <th class="text-center">Lembar Ikat</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-danger" colspan="10">Data detail is empty</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /list detail -->
        </div>
        <div class="margin-bottom-20"></div>
        
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
function create_temp_produksi()
{
    $.ajax({
        url: "<?= Url::to(['order/create-temp-produksi']) ?>",
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

var timeOut = 3000;
$(document).ready(function(){
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

    $("body").off("click","#list_biaya_produksi").on("click","#list_biaya_produksi", function(e){
        e.preventDefault();
        load_biaya_produksi($(this));
    });

    $("body").off("click","[data-button=\"create_biaya_produksi\"]");
    $("body").on("click","[data-button=\"create_biaya_produksi\"]", function(e){
        e.preventDefault();
        create_temp_produksi();
    });
});
</script>