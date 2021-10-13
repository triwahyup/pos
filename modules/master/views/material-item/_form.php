<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterialItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-material-item-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                                'data' => $type,
                                'options' => [
                                    'placeholder' => 'Type Barang',
                                    'readonly' => (!$model->isNewRecord) ? true : false,
                                ],
                            ]) ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'material_code')->widget(Select2::classname(), [
                                'data' => $material,
                                'options' => [
                                    'placeholder' => 'Material Type',
                                    'readonly' => (!$model->isNewRecord) ? true : false,
                                ],
                            ]) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 margin-top-30">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'satuan_code')->widget(Select2::classname(), [
                                'data' => $satuan,
                                'options' => ['placeholder' => 'Satuan'],
                            ]) ?>    
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'um_1')->textInput(['readonly' => true]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'harga_beli_1')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-temp' => 1, 
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                                'readonly' => (!$model->isNewRecord && !empty($model->harga_beli_1)) ? false : true,
                            ]
                        ]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'harga_jual_1')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-temp' => 1, 
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                                'readonly' => (!$model->isNewRecord && !empty($model->harga_jual_1)) ? false : true,
                            ]
                        ]) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'um_2')->textInput(['readonly' => true]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'harga_beli_2')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-temp' => 1, 
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                                'readonly' => (!$model->isNewRecord && !empty($model->harga_beli_2)) ? false : true,
                            ]
                        ]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'harga_jual_2')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-temp' => 1, 
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                                'readonly' => (!$model->isNewRecord && !empty($model->harga_jual_2)) ? false : true,
                            ]
                        ]) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'um_3')->textInput(['readonly' => true]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'harga_beli_3')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-temp' => 1, 
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                                'readonly' => (!$model->isNewRecord && !empty($model->harga_beli_3)) ? false : true,
                            ]
                        ]) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'harga_jual_3')->widget(MaskedInput::className(), [
                            'clientOptions' => [
                                'alias' =>  'decimal',
                                'groupSeparator' => ',',
                                'autoGroup' => true
                            ],
                            'options' => [
                                'data-temp' => 1, 
                                'data-align' => 'text-right',
                                'data-name' => 'iconbox',
                                'data-icons' => 'rupiah',
                                'readonly' => (!$model->isNewRecord && !empty($model->harga_jual_3)) ? false : true,
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'code')->textInput(['maxlength' => true , 'readonly' => true]) ?>
                    </div>
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'panjang')->textInput() ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'lebar')->textInput() ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'gram')->textInput() ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'group_material_code')->widget(Select2::classname(), [
                                'data' => $groupMaterial,
                                'options' => ['placeholder' => 'Group Material'],
                            ]) ?>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'group_supplier_code')->widget(Select2::classname(), [
                                'data' => $groupSupplier,
                                'options' => ['placeholder' => 'Group Supplier'],
                            ]) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 4]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function generateCode(type)
{
    $.ajax({
        url: "<?=Url::to(['material-item/generate-code']) ?>",
        type: "GET",
        data: {
            type: type
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[id^=\"mastermaterialitem-\"]:not(#mastermaterialitem-type_code)").val(null);
            $("#mastermaterialitem-code").val(o.code);
            
            $("#mastermaterialitem-material_code").empty();
            $.each(o.material, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#mastermaterialitem-material_code").append(opt);
            });
            $("#mastermaterialitem-material_code").val(null);

            $("#mastermaterialitem-satuan_code").empty();
            $.each(o.satuan, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#mastermaterialitem-satuan_code").append(opt);
            });
            $("#mastermaterialitem-satuan_code").val(null);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function um(code)
{
    $.ajax({
        url: "<?=Url::to(['material-item/um']) ?>",
        type: "GET",
        data: {
            code: code
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[id^=\"mastermaterialitem-harga_beli_\"]").attr("readonly", true);
            $("[id^=\"mastermaterialitem-harga_jual_\"]").attr("readonly", true);
            
            $("#mastermaterialitem-um_1").val(o.um_1);
            $("#mastermaterialitem-um_2").val(o.um_2);
            for(var a=1;a<=o.composite;a++){
                $("#mastermaterialitem-harga_beli_"+a).attr("readonly", false);
                $("#mastermaterialitem-harga_jual_"+a).attr("readonly", false);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

$(document).ready(function(){
    $("body").off("change","#mastermaterialitem-type_code")
    $("body").on("change","#mastermaterialitem-type_code", function(e){
        e.preventDefault();
        generateCode($(this).val());
    });

    $("body").off("change","#mastermaterialitem-satuan_code")
    $("body").on("change","#mastermaterialitem-satuan_code", function(e){
        e.preventDefault();
        um($(this).val());
    });
});
</script>