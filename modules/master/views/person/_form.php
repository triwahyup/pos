<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-person-form">
    <?php $form = ActiveForm::begin(); ?>
        <!-- Detail Alamat -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h4>Detail Alamat</h4>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'type_user')->widget(Select2::classname(), [
                        'data' => $typePerson,
                        'options' => ['placeholder' => 'Type Person'],
                    ]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'npwp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'term_in')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'kode_pos')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'address')->textarea(['rows' => 4]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'provinsi_id')->widget(Select2::classname(), [
                        'data' => $dataProvinsi,
                        'options' => ['placeholder' => 'Provinisi'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                <?= $form->field($model, 'kabupaten_id')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => [
                            'placeholder' => 'Kabupaten',
                            'value' => (!$model->isNewRecord) ? (!empty($model->kabupaten)) ? $model->kabupaten->name : '' : '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'kecamatan_id')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => [
                            'placeholder' => 'Kecamatan',
                            'value' => (!$model->isNewRecord) ? (!empty($model->kecamatan)) ? $model->kecamatan->name : '' : '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                <?= $form->field($model, 'kelurahan_id')->widget(Select2::classname(), [
                        'data' => [],
                        'options' => [
                            'placeholder' => 'Kelurahan',
                            'value' => (!$model->isNewRecord) ? (!empty($model->kelurahan)) ? $model->kelurahan->name : '' : '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
            </div>
            <div class="margin-bottom-20 full-width"></div>
        </div>
        <!-- /Detail Alamat -->
        <div class="margin-bottom-20 full-width"></div>
        <!-- Detail Informasi -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h4>Detail Informasi</h4>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'phone_1')->widget(MaskedInput::classname(), ['mask'=>'9999-99999-999']) ?>
                <?= $form->field($model, 'phone_2')->widget(MaskedInput::classname(), ['mask'=>'9999-99999-999']) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <?= $form->field($model, 'keterangan')->textarea(['rows' => 4]) ?>
            </div>
            <div class="margin-bottom-20 full-width"></div>
        </div>
        <!-- /Detail Informasi -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function listKabupaten(provinsiId, isNewRecord=false)
{
    $.ajax({
        url: "<?=Url::to(['person/list-kabupaten']) ?>",
        type: "GET",
        data: {
            provinsiId: provinsiId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            if(isNewRecord){
                $.each(o, function(index, value){
                    var opt = new Option(value.name, value.id, false, false);
                    $("#masterperson-kabupaten_id").append(opt);
                });
            }else{
                $("#masterperson-kabupaten_id").empty();
                $.each(o, function(index, value){
                    var opt = new Option(value.name, value.id, false, false);
                    $("#masterperson-kabupaten_id").append(opt);
                });
                $("#masterperson-kabupaten_id").val(null);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function listKecamatan(kecamatanId, isNewRecord=false)
{
    $.ajax({
        url: "<?=Url::to(['person/list-kecamatan']) ?>",
        type: "GET",
        data: {
            kecamatanId: kecamatanId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            if(isNewRecord){
                $.each(o, function(index, value){
                    var opt = new Option(value.name, value.id, false, false);
                    $("#masterperson-kecamatan_id").append(opt);
                });
            }else{
                $("#masterperson-kecamatan_id").empty();
                $.each(o, function(index, value){
                    var opt = new Option(value.name, value.id, false, false);
                    $("#masterperson-kecamatan_id").append(opt);
                });
                $("#masterperson-kecamatan_id").val(null);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function listKelurahan(kelurahanId, isNewRecord=false)
{
    $.ajax({
        url: "<?=Url::to(['person/list-kelurahan']) ?>",
        type: "GET",
        data: {
            kelurahanId: kelurahanId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            if(isNewRecord){
                $.each(o, function(index, value){
                    var opt = new Option(value.name, value.id, false, false);
                    $("#masterperson-kelurahan_id").append(opt);
                });
            }else{
                $("#masterperson-kelurahan_id").empty();
                $.each(o, function(index, value){
                    var opt = new Option(value.name, value.id, false, false);
                    $("#masterperson-kelurahan_id").append(opt);
                });
                $("#masterperson-kelurahan_id").val(null);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

$(document).ready(function(){
    // load list kabupaten
    $("body").off("change","#masterperson-provinsi_id").on("change","#masterperson-provinsi_id", function(e){
        e.preventDefault();
        listKabupaten($(this).val());
    });
    // load list kecamatan
    $("body").off("change","#masterperson-kabupaten_id").on("change","#masterperson-kabupaten_id", function(e){
        e.preventDefault();
        listKecamatan($(this).val());
    });
    // load list kelurahan
    $("body").off("change","#masterperson-kecamatan_id").on("change","#masterperson-kecamatan_id", function(e){
        e.preventDefault();
        listKelurahan($(this).val());
    });
});
$(function(){
    listKabupaten("<?=$model->provinsi_id?>", true);
    listKecamatan("<?=$model->kabupaten_id?>", true);
    listKelurahan("<?=$model->kecamatan_id?>", true);
});
</script>