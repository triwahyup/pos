<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

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
/* @var $model app\modules\master\models\MasterOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">    
            <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                <?= $form->field($model, 'code')->hiddenInput(['maxlength' => true])->label(false) ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'type_order')->widget(Select2::classname(), [
                        'data' => [1=>'Produksi', 2=>'Jasa'],
                        'options' => [
                            'placeholder' => 'Pilih Type Order',
                            'class' => 'select2',
                        ],
                    ]) ?>
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0"></div>
            <div class="col-lg-4 col-md-4 col-xs-12 padding-right-0">
                <div class="margin-top-20"></div>
                <div class="board-container">
                    <p class="title">Total Biaya</p>
                    <?= $form->field($model, 'total_biaya')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <fieldset class="fieldset-box padding-20">
                <legend>Detail Material</legend>
                <div class="form-container">
                    <div class="margin-top-20"></div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Pilih Material:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'item_code')->widget(Select2::classname(), [
                                'data' => [],
                                'options' => [
                                    'placeholder' => 'Pilih Item',
                                    'class' => 'select2',
                                    'data-temp' => 1,
                                ],
                                'pluginOptions' => [
                                    'minimumInputLength' => 2,
                                    'ajax' => [
                                        'url' => Url::to(['order/list-item']),
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
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Min Order:</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'qty_order_1')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'readonly' => true,
                                        'placeholder' => 'RIM',
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'qty_order_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'readonly' => true,
                                    'maxlength' => 3,
                                    'placeholder' => 'LB',
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'qty_order_3')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'readonly' => true,
                                    'maxlength' => 3,
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Harga Beli:</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'harga_beli_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'harga_beli_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'harga_beli_3')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Harga Jual:</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'harga_jual_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'harga_jual_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'harga_jual_3')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="margin-top-20"></div>
                        <?= $form->field($temp, 'id')->hiddenInput()->label(false) ?>
                        <?= $form->field($temp, 'order_code')->hiddenInput()->label(false) ?>
                        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Potong (PxL):</label>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'panjang')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Panjang', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'lebar')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Lebar', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Mesin / Jml. Warna:</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'mesin')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Mesin', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'jumlah_warna')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Jumlah Warna', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label>Lb. Potong / Objek:</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'potong')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Potong', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'objek')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Objek', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <label class="font-size-12">Harga Cetak / Lb. Ikat:</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'harga_cetak')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => [
                                                'data-align' => 'text-right',
                                                'data-name' => 'iconbox',
                                                'data-icons' => 'rupiah',
                                                'placeholder' => 'Harga Cetak',
                                                'data-temp' => 1
                                            ]
                                        ])->label(false) ?>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                    <?= $form->field($temp, 'lembar_ikat')->widget(MaskedInput::className(), [
                                            'clientOptions' => [
                                                'alias' =>  'decimal',
                                                'groupSeparator' => ',',
                                                'autoGroup' => true
                                            ],
                                            'options' => ['data-align' => 'text-right', 'placeholder' => 'Lembar Ikat', 'data-temp' => 1]
                                        ])->label(false) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                        <button class="btn btn-success margin-bottom-10 margin-top-10" data-button="create_temp">
                            <i class="fontello icon-plus"></i>
                            <span>Tambah Data Detail</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12">
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
function listItem(q)
{
    $.ajax({
        url: "<?=Url::to(['order/list-item'])?>",
		type: "GET",
		data: { q: q },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o.results[0], function(index, value){
                if(index!="id"){
                    $("#tempmasterorderdetail-"+index).val(value);
                }
            });
            for(var a=1;a<=o.results[0].composite;a++){
                $("#tempmasterorderdetail-qty_order_"+a).attr("readonly", false);
                if(a==1) $("#tempmasterorderdetail-qty_order_1").val(20);
            }
        },
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['order/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-table=\"detail\"] > tbody").html(o.model);
            console.log(o.total_biaya);
            $("#masterorder-total_biaya").val(o.total_biaya);
        },
        complete: function(){
            $("#tempmasterorderdetail-item_code").val("").trigger("change");
            setTimeout(function(){
                $("[id^=\"tempmasterorderdetail-qty_order_\"]").attr("readonly", true);
                $("[id^=\"tempmasterorderdetail-\"]").val("");
            }, 400);
        }
    });
}

function get_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['order/get-temp']) ?>",
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
                if(value=='item_code'){
                    $("#tempmasterorderdetail-"+index).val(value).trigger("change");
                }else{
                    $("#tempmasterorderdetail-"+index).val(value);
                }

                if(value == 'qty_order_1' && value == 'qty_order_2' && value == 'qty_order_3'){
                    if(value !== null){
                        $("#temppurchaseorderdetail-"+value).attr("readonly", false);
                    }
                }
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
        url: "<?= Url::to(['order/create-temp']) ?>",
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
        url: "<?= Url::to(['order/update-temp']) ?>",
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
        url: "<?= Url::to(['order/delete-temp']) ?>",
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

function create_temp_produksi(biaya, item)
{
    $.ajax({
        url: "<?= Url::to(['order/create-temp-produksi']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: {
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
        url: "<?= Url::to(['order/delete-temp-produksi']) ?>",
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

$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp();
    <?php endif; ?>
});

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#tempmasterorderdetail-item_code").on("change","#tempmasterorderdetail-item_code", function(e){
        e.preventDefault();
        listItem($(this).val());
    });

    $("body").off("input","#tempmasterorderdetail-qty_order_2");
    $("body").on("input","#tempmasterorderdetail-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        if(!$("#masterorder-name").val() && !$("#masterorder-type_order").val()){
            notification.open("danger", 'Nama dan Type Order tidak boleh kosong!', timeOut);
        }else{
            var success = true,
               message = "";
            $.each($("[data-temp]"), function(index, element){
                var a = $(element).attr("id").split("-"),
                    b = a[1].replace("_", " ");
                if(!$(element).val()){
                    success = false;
                    message += parsing.toUpper(b, 2)+', ';
                }
            });

            if(success){
                create_temp($(this));
            }else{
                notification.open("danger", message+' tidak boleh kosong!', timeOut);
            }
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

    $("body").off("click","#tambah_proses").on("click","#tambah_proses", function(e){
        e.preventDefault();
        $(".option-custom").show();
    });
    $("body").off("click",".option-custom > li:not([data-event])").on("click",".option-custom > li:not([data-event])", function(e){
        e.preventDefault();
        var biaya = $("#biaya", $(this)).val();
        var item = $("#item", $(this)).val();
        create_temp_produksi(biaya, item);
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