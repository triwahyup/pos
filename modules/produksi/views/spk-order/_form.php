<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Proses Produksi</h4>
                <hr />
            </div>
            <!-- FORM -->
            <div class="hidden">
                <?= $form->field($spkHistory, 'no_spk')->hiddenInput()->label(false) ?>
                <?= $form->field($spkHistory, 'item_code')->hiddenInput()->label(false) ?>
                <?= $form->field($spkHistory, 'proses_id')->hiddenInput()->label(false) ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <!-- Tgl. SPK -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Tgl SPK:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'tgl_spk')->widget(DatePicker::classname(), [
                                    'type' => DatePicker::TYPE_INPUT,
                                    'options' => [
                                        'placeholder' => 'dd-mm-yyyy',
                                    ],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd-mm-yyyy',
                                    ]
                                ])->label(false) ?>
                        </div>
                    </div>
                    <!-- Outsource -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Outsource:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'outsource_code')->widget(Select2::classname(), [
                                    'data' => $dataList['outsource'],
                                    'options' => [
                                        'placeholder' => 'Pilih Data Outsource',
                                        'class' => 'select2',
                                        'readonly' => true
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                    <!-- NoPol -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>No. Polisi:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'nopol')->textInput(['maxlength'=>true, 'readonly' => true])->label(false) ?>
                        </div>
                    </div>
                    <!-- No. SJ -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>No. SJ:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'no_sj')->textInput(['maxlength'=>true, 'readonly' => true])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <!-- Mesin Name -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Nama Mesin:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'mesin_code')->widget(Select2::classname(), [
                                    'data' => [],
                                    'options' => [
                                        'placeholder' => 'Pilih Nama Mesin',
                                        'class' => 'select2',
                                        'readonly' => true
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                    <!-- Operator Mesin -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Operator Mesin:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'user_id')->widget(Select2::classname(), [
                                    'data' => $dataList['operator'],
                                    'options' => [
                                        'placeholder' => 'Pilih Operator Mesin',
                                        'class' => 'select2',
                                        'readonly' => true
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                    <!-- QTY Proses -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>QTY Proses:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'qty_proses')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'readonly' => true
                                    ]
                                ])->label(false) ?>
                        </div>
                    </div>
                    <!-- Keterangan -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Keterangan:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkHistory, 'keterangan')->textarea(['rows'=>3, 'readonly' => true])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <?= Html::a('<i class="fontello icon-floppy"></i><span>Simpan</span>', 'javascript:void(0)', [
                        'class' => 'btn btn-success btn-flat btn-sm hidden', 'data-button' => 'create']) ?>
                <?= Html::a('<i class="fontello icon-cancel"></i><span>Cancel</span>', [
                        'cancel-proses', 'no_spk' => $model->no_spk], [
                        'class' => 'btn btn-danger btn-flat btn-sm hidden', 'data-button' => 'cancel']) ?>
            </div>
            <!-- /FORM -->
            <!-- DETAIL -->
            <div data-render="detail"></div>
            <!-- /DETAIL -->
            <?php if($model->status_produksi == 1): ?>
                <div class="col-lg-12 col-md-12 col-xs-12 text-right margin-bottom-20">
                    <?= Html::a('<i class="fontello icon-info-4 font-size-18"></i>
                        <span class="line-height-28 margin-left-30">Jika sudah input qty yang mau di proses, silahkan untuk melanjutkan ke proses produksi.</span>', 
                        ['post', 'no_spk'=>$model->no_spk, 'type' => \Yii::$app->params['IN_PROGRESS']], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
                </div>
            <?php elseif($model->status_produksi == 2): ?>
                <div class="col-lg-12 col-md-12 col-xs-12 text-right margin-bottom-20">
                    <?= Html::a('<i class="fontello icon-info-4 font-size-18"></i>
                        <span class="line-height-28 margin-left-30">Jika sudah selesai input hasil produksi, silahkan mereview kembali hasil produksi sebelum melanjutkan proses closing produksi.</span>', 
                        ['post', 'no_spk'=>$model->no_spk, 'type' => \Yii::$app->params['IN_REVIEW']], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="hidden" data-layout="print_layout"></div>
<div data-form="popup_hasil"></div>
<script>
function create(el)
{
    $.ajax({
        url: "<?= Url::to(['spk-order/create']) ?>",
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
                init_data(no_spk);
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function init_data(no_spk)
{
    $.ajax({
        url: "<?= Url::to(['spk-order/data-detail']) ?>",
        type: "GET",
        data: {
            no_spk: no_spk
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            _init_get_data("cancel");
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail\"]").html(o.data);
        },
        complete: function(){
            _init_get_data("cancel");
        }
    });
}

function get_data(data)
{
    $.ajax({
        url: "<?=Url::to(['spk-order/get-data'])?>",
        type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            proses_id: data.id,
            mesin_type: data.mesin,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                _init_get_data();
                
                $.each(o.model, function(index, value){
                    console.log(index, value);
                    $("#spkorderhistory-"+index).val(value).trigger("change");
                });
                $("#spkorderhistory-mesin_code").empty();
                $.each(o.mesin, function(index, value){
                    var opt = new Option(value.name, value.code, false, false);
                    $("#spkorderhistory-mesin_code").append(opt);
                });
                $("#spkorderhistory-mesin_code").val(null);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){}
    });
}

function print(data)
{
    $.ajax({
        url: "<?=Url::to(['spk-order/print'])?>",
		type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            proses_id: data.id,
            urutan: data.urutan
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-layout=\"print_layout\"]").html(o.data)
            var w = window.open(),
                newstr = $("[data-layout=\"print_layout\"]").html();
            $.get("css/print.min.css", function(css){
                w.document.write("<html>");
                    w.document.write("<head>");
                        w.document.write("<style>");
                            w.document.write(css);
                        w.document.write("</style>");
                    w.document.write("</head>");
                    w.document.write("<body>");
                        $(w.document.body).html(newstr);
                    w.document.write("</body>");
                w.document.write("</html>");
                w.print();
                w.close();
            });
        },
        complete: function(){}
    });
}

function popup_input(el)
{
    data = el.data();
    $.ajax({
        url: "<?=Url::to(['spk-order/popup-input'])?>",
		type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            proses_id: data.id,
            urutan: data.urutan
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
			el.loader("load");
		},
        success: function(data){
            $("[data-form=\"popup_hasil\"]").html(data);
        },
        complete: function(){
			el.loader("destroy");
		}
    });
}

function update(el)
{
    $.ajax({
        url: "<?= Url::to(['spk-order/update']) ?>",
        type: "POST",
        data: $("#form-hasil").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
                location.reload();
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

var timeOut = 7000,
    no_spk = "<?=$_GET['no_spk'] ?>";
var _init_get_data = function(type=null){
    if(type == 'cancel'){
        $("[id^=\"spkorderhistory-\"]").attr("readonly", true);
        $("[id^=\"spkorderhistory-\"]").val(null).trigger("change");
        $("[data-button=\"create\"]").removeClass("hidden").addClass("hidden");
        $("[data-button=\"cancel\"]").removeClass("hidden").addClass("hidden");
        $("[data-button=\"get_data\"]").prop("disabled", 0);
    }else{
        $("[id^=\"spkorderhistory-\"]").attr("readonly", false);
        $("[data-button=\"create\"]").removeClass("hidden");
        $("[data-button=\"cancel\"]").removeClass("hidden");
        $("[data-button=\"get_data\"]").prop("disabled", 1);
        $("html, body").animate({scrollTop:0}, 600);
    }
}
$(document).ready(function(){
    $("body").off("click","[data-button=\"create\"]").on("click","[data-button=\"create\"]", function(e){
        e.preventDefault();
        create($(this));
    });
    $("body").off("click","[data-button=\"cancel\"]").on("click","[data-button=\"cancel\"]", function(e){
        e.preventDefault();
        _init_get_data("cancel");
    });
    $("body").off("click","[data-button=\"get_data\"]").on("click","[data-button=\"get_data\"]", function(e){
        e.preventDefault();
        get_data($(this).data());
    });
    
    $("body").off("click","[data-button=\"print\"]").on("click","[data-button=\"print\"]", function(e){
        e.preventDefault();
        print($(this).data());
    });

    $("body").off("click","[data-button=\"popup_input\"]").on("click","[data-button=\"popup_input\"]", function(e){
        e.preventDefault();
        popup_input($(this));
    });
    $("body").off("click","[data-button=\"update\"]").on("click","[data-button=\"update\"]", function(e){
        e.preventDefault();
        update($(this));
    });
});
$(function(){
    init_data(no_spk);
});
</script>