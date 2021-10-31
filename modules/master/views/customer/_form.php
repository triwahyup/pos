<?php
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
        <fieldset class="fieldset-box">
            <legend>Detail Alamat</legend>
            <div class="margin-bottom-20 full-width"></div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'contact_person')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'npwp')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="margin-top-20"></div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'address')->textarea(['rows' => 4]) ?>
                    <?= $form->field($model, 'kode_pos')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'provinsi_id')->widget(Select2::classname(), [
                            'data' => $dataProvinsi,
                            'options' => ['placeholder' => 'Provinisi'],
                        ]) ?>
                    <?= $form->field($model, 'kabupaten_id')->widget(Select2::classname(), [
                            'data' => (!$model->isNewRecord) ? (isset($model->kabupaten)) ? [
                                $model->kabupaten->id => $model->kabupaten->name
                            ] : '' : [],
                            'options' => ['placeholder' => 'Kabupaten'],
                        ]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'kecamatan_id')->widget(Select2::classname(), [
                            'data' => (!$model->isNewRecord) ? (isset($model->kecamatan)) ? [
                                $model->kecamatan->id => $model->kecamatan->name,
                            ] : '' : [],
                            'options' => ['placeholder' => 'Kecamatan'],
                        ]) ?>
                    <?= $form->field($model, 'kelurahan_id')->widget(Select2::classname(), [
                            'data' => (!$model->isNewRecord) ? (isset($model->kelurahan)) ? [
                                $model->kelurahan->id => $model->kelurahan->name,
                            ] : '' : [],
                            'options' => ['placeholder' => 'Kelurahan'],
                        ]) ?>
                </div>
                <div class="margin-bottom-20 full-width"></div>
            </div>
        </fieldset>
        <div class="margin-bottom-20 full-width"></div>
        <fieldset class="fieldset-box">
            <legend>Detail Informasi</legend>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="margin-top-20"></div>
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
        </fieldset>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="margin-top-20"></div>
            <div class="text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function listKabupaten(provinsiId)
{
    $.ajax({
        url: "<?=Url::to(['customer/list-kabupaten']) ?>",
        type: "GET",
        data: {
            provinsiId: provinsiId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#masterperson-kabupaten_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.id, false, false);
                $("#masterperson-kabupaten_id").append(opt);
            });
            $("#masterperson-kabupaten_id").val(null);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function listKecamatan(kecamatanId)
{
    $.ajax({
        url: "<?=Url::to(['customer/list-kecamatan']) ?>",
        type: "GET",
        data: {
            kecamatanId: kecamatanId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#masterperson-kecamatan_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.id, false, false);
                $("#masterperson-kecamatan_id").append(opt);
            });
            $("#masterperson-kecamatan_id").val(null);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function listKelurahan(kelurahanId)
{
    $.ajax({
        url: "<?=Url::to(['customer/list-kelurahan']) ?>",
        type: "GET",
        data: {
            kelurahanId: kelurahanId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#masterperson-kelurahan_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.id, false, false);
                $("#masterperson-kelurahan_id").append(opt);
            });
            $("#masterperson-kelurahan_id").val(null);
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
</script>