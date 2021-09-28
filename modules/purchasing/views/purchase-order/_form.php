<?php
use kartik\date\DatePicker;
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
/* @var $model app\modules\purchasing\models\PurchaseOrder */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="purchase-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_po')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_po')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'yyyy-mm-dd',
                            'value' => date('Y-m-d'),
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="margin-top-20"></div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'supplier_code')->widget(Select2::classname(), [
                            'data' => $supplier,
                            'options' => ['placeholder' => 'Supplier'],
                        ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'term_in')->textInput() ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_kirim')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'yyyy-mm-dd',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            ]]) ?>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'user_request')->widget(Select2::classname(), [
                            'data' => $profile,
                            'options' => ['placeholder' => 'Request By'],
                        ]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <div class="board-container">
                <p class="title">Total Order</p>
                <?= $form->field($model, 'total_order')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <fieldset class="fieldset-box padding-20">
                <legend>Detail Item</legend>
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
                                ],
                                'pluginOptions' => [
                                    'minimumInputLength' => 2,
                                    'ajax' => [
                                        'url' => Url::to(['purchase-order/list-item']),
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
                            <label>QTY Order:</label>
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
                            <label>UM:</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'um_1')->textInput(['readonly' => true])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'um_2')->textInput(['readonly' => true])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'um_3')->textInput(['readonly' => true])->label(false) ?>
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
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>PPN (%):</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'ppn')->textInput(['data-align' => 'text-right'])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0 hidden">
                            <?= $form->field($temp, 'id')->textInput() ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
                        <button class="btn btn-success margin-bottom-20" data-button="create_temp">
                            <i class="fontello icon-plus"></i>
                            <span>Tambah Data Detail</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                        <table class="table table-bordered table-custom" data-table="detail">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center" colspan="3">QTY</th>
                                    <th class="text-center" colspan="3">Harga Beli</th>
                                    <th class="text-center">Ppn (%)</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($model->temps) > 0): 
                                    $totalOrder=0; ?>
                                    <?php foreach($model->temps as $index=>$val): 
                                        $totalOrder += $val->total_order; ?>
                                        <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                            <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                            <?php for($a=1;$a<=3;$a++): ?>
                                                <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                            <?php endfor; ?>
                                            <?php for($a=1;$a<=3;$a++): ?>
                                                <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
                                            <?php endfor; ?>
                                            <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
                                            <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="update_temp">
                                                    <i class="fontello icon-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="delete_temp">
                                                    <i class="fontello icon-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td class="text-right" colspan="9"><strong>Total Order:</strong></td>
                                        <td class="text-right"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td class="text-center text-danger" colspan="15">Data is empty</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
        url: "<?=Url::to(['purchase-order/list-item'])?>",
		type: "GET",
		data: { q: q },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o.results[0], function(index, value){
                if(index != 'id'){
                    $("#temppurchaseorderdetail-"+index).val(value);
                }
            });
            for(var a=1;a<=o.results[0].composite;a++){
                $("#temppurchaseorderdetail-qty_order_"+a).attr("readonly", false);
            }
        },
        complete: function(){}
    });
}

function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['purchase-order/temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-table=\"detail\"] > tbody").html(o.model);
            $("#purchaseorder-total_order").val(o.total_order);
        },
        complete: function(){
            $("#temppurchaseorderdetail-item_code").val("").trigger("change");
            setTimeout(function(){
                $("[id^=\"temppurchaseorderdetail-qty_order_\"]").attr("readonly", true);
                $("[id^=\"temppurchaseorderdetail-\"]").val("");
            }, 400);
        }
    });
}

function get_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['purchase-order/get-temp']) ?>",
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
                if(index=='item_code'){
                    $("#temppurchaseorderdetail-"+index).val(value).trigger("change");
                }else{
                    $("#temppurchaseorderdetail-"+index).val(value);
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
        url: "<?= Url::to(['purchase-order/create-temp']) ?>",
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
        url: "<?= Url::to(['purchase-order/update-temp']) ?>",
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
        url: "<?= Url::to(['purchase-order/delete-temp']) ?>",
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
    $("body").off("change","#temppurchaseorderdetail-item_code");
    $("body").on("change","#temppurchaseorderdetail-item_code", function(e){
        e.preventDefault();
        listItem($(this).val());
    });

    $("body").off("input","#temppurchaseorderdetail-qty_order_2");
    $("body").on("input","#temppurchaseorderdetail-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        var success = true,
            message = "";
        $.each($("[id^=\"purchaseorder-\"]:not([id=\"purchaseorder-keterangan\"]):not([id=\"purchaseorder-ppn\"]):not([id=\"purchaseorder-id\"])"), function(index, element){
            var a = $(element).attr("id").split("-"),
                b = a[1].replace("_", " ");
            if(!$(element).val()){
                success = false;
                message += parsing.toUpper(b, 2)+', ';
            }
        });
        if(!$("#temppurchaseorderdetail-qty_order_1").val() || $("#temppurchaseorderdetail-qty_order_1").val() == ""){
            success = false;
            message = 'QTY Order 1';
        }
        if(success){
            create_temp($(this));
        }else{
            notification.open("danger", message+' tidak boleh kosong!', timeOut);
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
</script>