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
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                        'data' => $type,
                        'options' => ['placeholder' => 'Type Barang'],
                    ]) ?>
                <?= $form->field($model, 'code')->textInput(['maxlength' => true , 'readonly' => true]) ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'material_code')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => ['placeholder' => 'Material Type'],
                    ]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'satuan_code')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => ['placeholder' => 'Satuan'],
                    ]) ?>
                <?= $form->field($model, 'group_material_code')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => ['placeholder' => 'Group Material'],
                    ]) ?>
                <?= $form->field($model, 'group_supplier_code')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => ['placeholder' => 'Group Supplier'],
                    ]) ?>
                <?= $form->field($model, 'panjang')->textInput() ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'lebar')->textInput() ?>
                <?= $form->field($model, 'gram')->textInput() ?>
                <?= $form->field($model, 'harga_beli')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                    'options' => [
                        'data-temp' => 1, 
                        'data-align' => 'text-right'
                    ]
                ]) ?>
                <?= $form->field($model, 'harga_jual')->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' =>  'decimal',
                        'groupSeparator' => ',',
                        'autoGroup' => true
                    ],
                    'options' => [
                        'data-temp' => 1, 
                        'data-align' => 'text-right'
                    ]
                ]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>
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

$(document).ready(function(){
    $("body").off("change","#mastermaterialitem-type_code")
    $("body").on("change","#mastermaterialitem-type_code", function(e){
        e.preventDefault();
        generateCode($(this).val());
    });
});
</script>