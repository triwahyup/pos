<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$resultsJs = <<< JS
    function (data, params) {
       params.page = params.page || 1;
        return {
            // Change `data.items` to `data.results`.
            // `results` is the key that you have been selected on
            // `actionJsonlist`.
            results: data.results
        };
    }
JS;

$format1line = <<< JS
	function (item) {
		var selectionText = item.text;
		var returnString = '<span class>'+selectionText+'</span>';
		return returnString; 
	}
JS;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'customer_code')->widget(Select2::classname(), [
                            'data' => $customer,
                            'options' => [
                                'placeholder' => 'Pilih Type Order',
                                'class' => 'select2',
                            ],
                        ]) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_so')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'yyyy-mm-dd',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_po')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_po')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'yyyy-mm-dd',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'ppn')->textInput() ?>
                </div>
                <div class="col-lg-5 col-md-5 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                <div class="margin-top-20"></div>
                <div class="board-container">
                    <p class="title">Total Order</p>
                    <?= $form->field($model, 'total_order')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20 padding-left-0">
            <div class="margin-top-20"></div>
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'nama_order')->widget(Select2::classname(), [
                    'data' => [],
                    'options' => [
                        'placeholder' => 'Pilih Order',
                        'class' => 'select2',
                        'data-temp' => 1,
                    ],
                    'pluginOptions' => [
                        'minimumInputLength' => 2,
                        'ajax' => [
                            'url' => Url::to(['sales-order/list-order']),
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression("function(params) {
                                return {
                                    q:params.term,
                                }
                            }"),
                            'cache' => true,
                            'processResults' => new JsExpression($resultsJs),
                        ],
                        'templateResult' => new JsExpression($format1line),
                        'templateSelection' => new JsExpression($format1line),
                        'escapeMarkup' => new JsExpression("function(markup) { return markup; }")
                    ],
                ])->label(false) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($temp, 'outsource_code')->widget(Select2::classname(), [
                        'data' => $outsourcing,
                        'options' => [
                            'placeholder' => 'Pilih Jasa',
                            'class' => 'select2',
                            'data-temp' => 1,
                            'readonly' => true,
                        ],
                    ])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <fieldset class="fieldset-box padding-20">
                <legend>Detail Material</legend>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <table class="table table-bordered table-custom" data-table="detail">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item</th>
                                <th class="text-center" colspan="3">QTY</th>
                                <th class="text-center" colspan="3">QTY Detail</th>
                                <th class="text-center">Harga Cetak</th>
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
<script>
function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-table=\"detail\"] > tbody").html(o.model);
            $("#salesorder-total_order").val(o.total_order);
        },
        complete: function(){}
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/create-temp']) ?>",
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
        url: "<?= Url::to(['sales-order/delete-temp']) ?>",
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
        url: "<?= Url::to(['sales-order/create-temp-produksi']) ?>",
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
        url: "<?= Url::to(['sales-order/delete-temp-produksi']) ?>",
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

    $("body").off("click","[id^=\"tambah_proses_\"]").on("click","[id^=\"tambah_proses_\"]", function(e){
        e.preventDefault();
        $(this).next(".option-custom").show();
    });
    $("body").off("click",".option-custom > li:not([data-event])").on("click",".option-custom > li:not([data-event])", function(e){
        e.preventDefault();
        create_temp_produksi($("#code", $(this)).val(), $("#biaya", $(this)).val(), $("#item", $(this)).val());
    });
    $("body").off("click","#delete_temp").on("click","#delete_temp", function(e){
        e.preventDefault();
        delete_temp_produksi($(this).attr("data-id"));
    });
    $("body").off("click","li[data-event=\"close\"]").on("click","li[data-event=\"close\"]", function(e){
        e.preventDefault();
        $(".option-custom").hide();
    });
});
</script>