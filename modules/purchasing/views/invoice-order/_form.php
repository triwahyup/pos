<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrderInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-order-invoice-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_po')->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_po')->textInput(['readonly' => true]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="margin-top-30"></div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'no_bukti')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'tgl_invoice')->widget(DatePicker::classname(), [
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
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'keterangan')->textarea(['rows' => 2]) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <div class="board-container">
                <p class="title">Total Invoice</p>
                <?= $form->field($model, 'total_invoice')->textInput(['readonly' => true, 'value'=>0])->label(false) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <fieldset class="fieldset-box padding-20">
                <legend>Detail Item</legend>
                <div class="form-container">
                    <div class="margin-top-20"></div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>QTY Terima:</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'qty_terima_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-temp' => 1, 
                                    'data-align' => 'text-right'
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'qty_terima_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-temp' => 1, 
                                    'data-align' => 'text-right'
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'qty_terima_3')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-temp' => 1, 
                                    'data-align' => 'text-right'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <label>Harga Beli:</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'harga_beli_1')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' =>  'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-name' => 'iconbox',
                                        'data-icons' => 'rupiah',
                                        'data-temp' => 1,
                                        'readonly' => true,
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'harga_beli_2')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' =>  'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-name' => 'iconbox',
                                        'data-icons' => 'rupiah',
                                        'data-temp' => 1,
                                        'readonly' => true,
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'harga_beli_3')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' =>  'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-name' => 'iconbox',
                                        'data-icons' => 'rupiah',
                                        'data-temp' => 1,
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
                            <?= $form->field($model, 'ppn')->textInput(['data-temp' => 1, 'data-align' => 'text-right'])->label(false) ?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'no_invoice')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'urutan')->hiddenInput(['data-temp' => 1])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <button class="btn btn-success margin-bottom-20 hidden" data-button="create_temp">
                                <i class="fontello icon-plus"></i>
                                <span>Tambah Data Detail</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <table class="table table-bordered table-custom" data-table="detail">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center" colspan="3">QTY Order</th>
                                    <th class="text-center" colspan="3">QTY Terima</th>
                                    <th class="text-center" colspan="3">Harga Beli</th>
                                    <th class="text-center">Ppn (%)</th>
                                    <th class="text-center">Total Order</th>
                                    <th class="text-center">Total Invoice</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $totalOrder=0;
                                    foreach($model->details as $index=>$val): 
                                    $totalOrder += $val->total_order; ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                        <?php for($a=1;$a<=3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <?php for($a=1;$a<=3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_terima_'.$a])) ? number_format($val['qty_terima_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <?php for($a=1;$a<=3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
                                        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                        <td class="text-right"><?=number_format($val->total_invoice).'.-' ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-xs btn-sm" data-invoice="<?=$val->no_invoice ?>" data-urutan="<?=$val->urutan ?>" data-button="update_temp">
                                                <i class="fontello icon-pencil"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
function init_temp()
{
    $.ajax({
        url: "<?= Url::to(['invoice-order/temp']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-table=\"detail\"] > tbody").html(o.model);
            $("#purchaseorderinvoice-total_invoice").val(o.total_invoice);
        },
        complete: function(){
            $("[data-button=\"create_temp\"]").removeClass("hidden").addClass("hidden");
            setTimeout(function(){
                $("[data-temp]").val("")
            }, 400);
        }
    });
}

function get_temp(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['invoice-order/get-temp']) ?>",
        type: "GET",
        dataType: "text",
        data: {
            no_invoice: data.invoice,
            urutan: data.urutan
        },
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#purchaseorderinvoice-"+index).val(value);
            });
        },
        complete: function(){
            temp.init();
        }
    });
}

function update_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['invoice-order/update-temp']) ?>",
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

var timeOut = 3000;
<?php if(!$model->isNewRecord): ?>
    init_temp();
<?php endif; ?>
$(document).ready(function(){
    $("body").off("click","[data-button=\"update_temp\"]").on("click","[data-button=\"update_temp\"]", function(e){
        e.preventDefault();
        get_temp($(this));
    });
    $("body").off("click","[data-button=\"change_temp\"]").on("click","[data-button=\"change_temp\"]", function(e){
        e.preventDefault();
        update_temp($(this));
    });
});
</script>