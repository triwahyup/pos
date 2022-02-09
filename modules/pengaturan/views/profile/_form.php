<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-form">
    <?php $form = ActiveForm::begin(); ?>
        <fieldset class="fieldset-box">
            <legend>Detail Personal</legend>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="margin-top-20"></div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'nik')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'nip')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'tgl_lahir')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'dd-mm-yyyy',
                            'value' => (!empty($model->tgl_lahir)) ? date('d-m-Y', strtotime($model->tgl_lahir)) : null,
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]]) ?>
                    <?= $form->field($model, 'tempat_lahir')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group-layer margin-bottom-20">
                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        <?php if($model->isNewRecord): ?>
                            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                        <?php endif; ?>
                        <?php if(!$model->isNewRecord): ?>
                            <label class="text-danger">Masukkan password anda jika ingin merubah password baru.</label>
                            <?= $form->field($model, 'current_password')->passwordInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'new_password')->passwordInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'retype_new_password')->passwordInput(['maxlength' => true]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="margin-bottom-20 full-width"></div>
        <fieldset class="fieldset-box">
            <legend>Detail Alamat</legend>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="margin-top-20"></div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'alamat')->textarea(['rows' => 4]) ?>
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
                    <?= $form->field($model, 'golongan')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'tgl_masuk')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'dd-mm-yyyy',
                            'value' => (!empty($model->tgl_masuk)) ? date('d-m-Y', strtotime($model->tgl_masuk)) : null,
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]]) ?>
                    <?= $form->field($model, 'tgl_keluar')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'options' => [
                            'placeholder' => 'dd-mm-yyyy',
                            'value' => (!empty($model->tgl_keluar)) ? date('d-m-Y', strtotime($model->tgl_keluar)) : null,
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'phone_1')->widget(MaskedInput::classname(), ['mask'=>'9999-99999-999']) ?>
                    <?= $form->field($model, 'phone_2')->widget(MaskedInput::classname(), ['mask'=>'9999-99999-999']) ?>
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <?= $form->field($model, 'typeuser_code')->widget(Select2::classname(), [
                            'data' => $typeUser,
                            'options' => ['placeholder' => 'Kabupaten'],
                        ]) ?>
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
        url: "<?=Url::to(['profile/list-kabupaten']) ?>",
        type: "GET",
        data: {
            provinsiId: provinsiId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#profile-kabupaten_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.id, false, false);
                $("#profile-kabupaten_id").append(opt);
            });
            $("#profile-kabupaten_id").val(null);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function listKecamatan(kecamatanId)
{
    $.ajax({
        url: "<?=Url::to(['profile/list-kecamatan']) ?>",
        type: "GET",
        data: {
            kecamatanId: kecamatanId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#profile-kecamatan_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.id, false, false);
                $("#profile-kecamatan_id").append(opt);
            });
            $("#profile-kecamatan_id").val(null);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

function listKelurahan(kelurahanId)
{
    $.ajax({
        url: "<?=Url::to(['profile/list-kelurahan']) ?>",
        type: "GET",
        data: {
            kelurahanId: kelurahanId
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#profile-kelurahan_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.id, false, false);
                $("#profile-kelurahan_id").append(opt);
            });
            $("#profile-kelurahan_id").val(null);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){},
        complete: function(){}
    });
}

$(document).ready(function(){
    // load list kabupaten
    $("body").off("change","#profile-provinsi_id").on("change","#profile-provinsi_id", function(e){
        e.preventDefault();
        listKabupaten($(this).val());
    });
    // load list kecamatan
    $("body").off("change","#profile-kabupaten_id").on("change","#profile-kabupaten_id", function(e){
        e.preventDefault();
        listKecamatan($(this).val());
    });
    // load list kelurahan
    $("body").off("change","#profile-kecamatan_id").on("change","#profile-kecamatan_id", function(e){
        e.preventDefault();
        listKelurahan($(this).val());
    });
});
</script>