<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Hasil Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="hasil-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="text-right">
                <a href="javascript:void(0)" id="hidden_detail_spk">
                    <span>List SPK >></span>
                </a>
                <hr class="margin-top-5 margin-bottom-5" />
            </div>
            <div data-toggle>
                <?php if(count($inProgress) > 0): ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="margin-top-20"></div>
                        <h6 class="font-size-16"><strong>List SPK:</strong></h6>
                        <ul class="custom-detail">
                            <li>
                                <span class="font-size-12 font-bold width-100"><u>No. SPK</u></span>
                                <span class="font-size-12 font-bold width-100"><u>Tgl. SPK</u></span>
                                <span class="font-size-12 font-bold width-100"><u>Status</u></span>
                            </li>
                            <?php foreach($inProgress as $val): ?>
                                <li class="margin-bottom-5">
                                    <a href="<?=\Yii::$app->getUrlManager()->createUrl(['produksi/spk/view', 'no_spk'=>$val->no_spk]) ?>" target="_blank">
                                        <span class="font-size-12 font-bold width-100"><?=$val->no_spk ?></span>
                                    </a>
                                    <span class="font-size-12 width-100"><?=$val->tgl_spk ?></span>
                                    <span class="font-size-12 width-200"><?=$val->statusProduksi() ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <hr class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0" />
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="margin-top-20"></div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <label class="font-size-12">Pilih No. SPK:</label>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                <?= $form->field($spkProses, 'no_spk')->widget(Select2::classname(), [
                        'data' => $noSPK,
                        'options' => ['placeholder' => 'Pilih No. SPK', 'class' => 'select2'],
                    ])->label(false) ?>
            </div>
        </div>
        <div data-render="load_form">
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function list_proses(no_spk)
{
    $.ajax({
        url: "<?=Url::to(['hasil/list-proses'])?>",
		type: "GET",
        data: {
            no_spk: no_spk,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"load_form\"]").html(o.data);
        },
        complete: function(){}
    });
}

function update_proses(el)
{
    $.ajax({
        url: "<?= Url::to(['hasil/update-proses']) ?>",
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
                setTimeout(function(){
                    location.reload();
                }, 500);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#spkdetailproses-no_spk").on("change","#spkdetailproses-no_spk", function(e){
        e.preventDefault();
        list_proses($(this).val());
    });

    $("body").off("click","[data-button=\"update_proses\"]").on("click","[data-button=\"update_proses\"]", function(e){
        e.preventDefault();
        update_proses($(this));
    });
});
</script>