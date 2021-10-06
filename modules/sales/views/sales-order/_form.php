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
                <?= $form->field($model, 'nama_order')->widget(Select2::classname(), [
                    'data' => [],
                    'options' => [
                        'placeholder' => 'Pilih Order',
                        'class' => 'select2',
                        'value' => (!$model->isNewRecord) ? $model->order->name : '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
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
                ]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 padding-left-0">
                <?= $form->field($model, 'outsource_code')->widget(Select2::classname(), [
                        'data' => $outsourcing,
                        'options' => [
                            'placeholder' => 'Pilih Jasa',
                            'class' => 'select2',
                            'readonly' => true,
                        ],
                    ]) ?>
            </div>
            <?= $form->field($model, 'order_code')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'type_order')->hiddenInput()->label(false) ?>
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
function load_data_order(el)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/load-order']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: {
            code: el.val(),
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                if(o.type_order == 1){
                    $("#salesorder-outsource_code").attr("readonly", true);
                }else{
                    $("#salesorder-outsource_code").attr("readonly", false);
                }
                console.log(o);
                $("#salesorder-order_code").val(o.order_code);
                $("#salesorder-type_order").val(o.type_order);
            }
            init_temp();
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

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
            $("#salesorder-total_order").val(o.total_biaya);
        },
        complete: function(){}
    });
}

function create_temp_produksi(el)
{
    var no_so = "<?=(isset($_GET['no_so'])) ? $_GET['no_so'] : '' ?>"
    $.ajax({
        url: "<?= Url::to(['sales-order/create-temp-produksi']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: {
            code: $("#code", el).val(),
            biaya: $("#biaya", el).val(),
            item: $("#item", el).val(),
            no_so: no_so,
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
    $("body").off("change","#salesorder-nama_order").on("change","#salesorder-nama_order", function(e){
        e.preventDefault();
        load_data_order($(this));
    });

    $("body").off("click","[id^=\"tambah_proses_\"]").on("click","[id^=\"tambah_proses_\"]", function(e){
        e.preventDefault();
        $(this).next(".option-custom").show();
    });
    $("body").off("click",".option-custom > li:not([data-event])").on("click",".option-custom > li:not([data-event])", function(e){
        e.preventDefault();
        create_temp_produksi($(this));
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