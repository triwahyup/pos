<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
            <!-- HEADER -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Job</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Nama Job -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Nama Job:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
                        <?php if(!$model->isNewRecord): ?>
                            <?= $form->field($model, 'code')->hiddenInput(['maxlength' => true])->label(false) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Type Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Type Order:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'type_order')->widget(Select2::classname(), [
                                'data' => [1=>'Produksi', 2=>'Jasa'],
                                'options' => [
                                    'placeholder' => 'Pilih Type Order',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Customer -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Customer:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'customer_code')->widget(Select2::classname(), [
                                'data' => $customer,
                                'options' => [
                                    'placeholder' => 'Pilih Customer',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Tgl. SO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl SO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'tgl_so')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'yyyy-mm-dd',
                                'value' => date('Y-m-d'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]])->label(false) ?>
                    </div>
                </div>
                <!-- No. PO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. PO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_po')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>
                <!-- Tgl. PO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl PO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'tgl_po')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'yyyy-mm-dd',
                                'value' => date('Y-m-d'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                            ]])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Ekspedisi -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Ekspedisi:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'ekspedisi_name')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>
                <!-- Biaya Pengiriman -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Biaya Pengiriman:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'biaya_pengiriman')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-name' => 'iconbox',
                                    'data-icons' => 'rupiah',
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Up Produksi -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Up Produksi:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'up_produksi')->dropDownList(['5' => '5%', '10' => '10%'], ['prompt'=>'Produksi Up (%)'])->label(false) ?>
                    </div>
                </div>
                <!-- PPN -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>PPN:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'ppn')->textInput()->label(false) ?>
                    </div>
                </div>
                <!-- Keterangan -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Keterangan:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 2])->label(false) ?>
                    </div>
                </div>
            </div>
            <!-- /HEADER -->
            <!-- ITEM -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Item</h4>
                <hr />
                <?php if(!$model->isNewRecord): ?>
                    <?= $form->field($tempItem, 'id')->hiddenInput()->label(false) ?>
                    <?= $form->field($tempItem, 'code')->hiddenInput()->label(false) ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Item Name -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Material:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'item_name')->textInput(['placeholder' => 'Pilih material tekan F4'])->label(false) ?>
                        <?= $form->field($tempItem, 'item_code')->hiddenInput()->label(false) ?>
                    </div>
                </div>
                <!-- Min Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Min Order:</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'qty_order_1')->widget(MaskedInput::className(), [
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
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'qty_order_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'readonly' => true,
                                    'maxlength' => 3,
                                    'placeholder' => 'LB',
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
            </div>
            <!-- /ITEM -->
            <!-- DETAIL -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Panjang/Lebar -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Potong (PxL):</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'panjang')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'placeholder' => 'Panjang'
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'lebar')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'placeholder' => 'Lebar'
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Potong/Objek -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Total Potong / Objek:</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'total_potong')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'placeholder' => 'Potong'
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'total_objek')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true, 
                                    'placeholder' => 'Objek'
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Warna -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0  padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Total Warna:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'total_warna')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right', 
                                    'data-temp' => true,
                                    'placeholder' => 'Total Warna'
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Lb. Ikat -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0  padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Lb.Ikat</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'satuan_ikat_code')->dropDownList($typeSatuan, [
                                'prompt'=>'Satuan Lb.Ikat', 'data-temp'=>true])->label(false) ?>
                    </div>
                </div>
                <!-- Satuan Lb. Ikat -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0  padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Satuan Lb.Ikat:</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'lembar_ikat_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-um' => 'DOS',
                                    'placeholder' => 'DOS',
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempDetail, 'lembar_ikat_um_1')->hiddenInput(['data-temp' => true, 'data-value' => 'DOS'])->label(false) ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'lembar_ikat_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-um' => 'IKAT',
                                    'placeholder' => 'IKAT',
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempDetail, 'lembar_ikat_um_2')->hiddenInput(['data-temp' => true, 'data-value' => 'IKAT'])->label(false) ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'lembar_ikat_3')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'placeholder' => 'PCS',
                                    'data-um' => 'PCS',
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempDetail, 'lembar_ikat_um_3')->hiddenInput(['data-temp' => true, 'data-value' => 'PCS'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Keterangan Cetak -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Keterangan Cetak:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'keterangan_cetak')->textarea(['data-temp'=>true])->label(false) ?>
                    </div>
                </div>
                <!-- Keterangan Potong -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Keterangan Potong:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'keterangan_potong')->textarea(['data-temp'=>true])->label(false) ?>
                    </div>
                </div>
                <!-- Keterangan Pond -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Keterangan Pond:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempDetail, 'keterangan_pond')->textarea(['data-temp'=>true])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <button class="btn btn-success margin-bottom-20" data-button="create_temp">
                    <i class="fontello icon-plus"></i>
                    <span>Tambah Data Detail</span>
                </button>
            </div>
            <!-- /DETAIL -->
            <!-- VIEW DETAIL TEMP -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div render="detail" data-render="detail" class="form-container padding-bottom-5 hidden"></div>
            </div>
            <!-- /VIEW DETAIL TEMP -->
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
function load_item()
{
    $.ajax({
        url: "<?=Url::to(['sales-order/list-item'])?>",
		type: "GET",
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function search_item(code)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/search'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            popup.close();
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function select_item(code)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/item'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#tempsalesorderitem-"+index).val(value);
            });
            for(var a=1;a<=o.composite;a++){
                $("#tempsalesorderitem-qty_order_"+a).attr("readonly", false);
            }
            $("#tempsalesorderitem-qty_order_1").val(20);
            $("#tempsalesorderitem-qty_order_2").val(0);
        },
        complete: function(){
            popup.close();
        }
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
                init_temp();
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

function delete_temp_item(id)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/delete-temp-item']) ?>",
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

function delete_temp_detail(id)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/delete-temp-detail']) ?>",
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

function load_proses_produksi()
{
    $.ajax({
        url: "<?=Url::to(['sales-order/list-proses-produksi'])?>",
		type: "POST",
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Proses Produksi',
				styleOptions: {
					width: 300
				}
			});
        },
        complete: function(){}
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
            $("[data-render=\"detail\"]").html(o.model);
            $("[data-render=\"detail\"]").removeClass("hidden");
            $("[id^=\"tempsalesorderdetail-lembar_ikat_\"]").attr("readonly", 1);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("keydown","#tempsalesorderitem-item_name")
    $("body").on("keydown","#tempsalesorderitem-item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item();
        }
    });

    $("body").off("click","[data-id=\"popup\"] table > tbody tr[data-code]");
    $("body").on("click","[data-id=\"popup\"] table > tbody tr[data-code]", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_item(data.code);
    });

    $("body").off("input","#tempsalesorderitem-qty_order_2");
    $("body").on("input","#tempsalesorderitem-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

    $("body").off("change","#tempsalesorderdetail-satuan_ikat_code");
    $("body").on("change","#tempsalesorderdetail-satuan_ikat_code", function(e){
        e.preventDefault();
        var text = $(this).find("option:selected").text();
        $("[data-um]").attr("readonly", true);
        $("[data-value]").val(null);
        $.each(text.split("-"), function(index, value){
            $("[data-um=\""+value+"\"]").attr("readonly", false);
            $("[data-value=\""+value+"\"]").val(value);
        });
    });

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        success = true;
        $.each($("[aria-required]:not([readonly])"), function(index, element){
            var a = $(element).attr("id").split("-"),
                b = a[1].replace("_", " ");
            if(!$(element).val()){
                success = false;
                errorMsg = parsing.toUpper(b, 2) +' cannot be blank.';
                if($(element).parent().hasClass("input-container")){
                    $(element).parent(".input-container").parent().removeClass("has-error").addClass("has-error");
                    $(element).parent(".input-container").siblings("[class=\"help-block\"]").text(errorMsg);
                }else{
                    $(element).parent().removeClass("has-error").addClass("has-error");
                    $(element).siblings("[class=\"help-block\"]").text(errorMsg);
                }
            }
        });
        if(success){
            create_temp($(this));
        }
    });

    $("body").off("click","[data-button=\"delete_item_temp\"]");
    $("body").on("click","[data-button=\"delete_item_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete_item_temp",
			target: data.id,
		});
    });
    $("body").off("click","#delete_item_temp").on("click","#delete_item_temp", function(e){
        e.preventDefault();
        delete_temp_item($(this).attr("data-target"));
    });

    $("body").off("click","[data-button=\"delete_detail_temp\"]");
    $("body").on("click","[data-button=\"delete_detail_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete_detail_temp",
			target: data.id,
		});
    });
    $("body").off("click","#delete_detail_temp").on("click","#delete_detail_temp", function(e){
        e.preventDefault();
        delete_temp_detail($(this).attr("data-target"));
    });

    $("body").off("click","[data-button=\"create_biaya_produksi_temp\"]");
    $("body").on("click","[data-button=\"create_biaya_produksi_temp\"]", function(e){
        e.preventDefault();
        load_proses_produksi();
    });
});

$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp();
    <?php endif; ?>
});
</script>