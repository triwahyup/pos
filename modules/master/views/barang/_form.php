<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="master-barang-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'type')->widget(Select2::classname(), [
                    'data' => $type,
                    'options' => ['placeholder' => 'Type Barang'],
                ]) ?>
            <?= $form->field($model, 'jenis')->widget(Select2::classname(), [
                    'data' => [],
                    'options' => ['placeholder' => 'Jenis Barang'],
                ]) ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($model, 'panjang')->textInput() ?>
            <?= $form->field($model, 'lebar')->textInput() ?>
            <?= $form->field($model, 'gram')->textInput() ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
            <?= $form->field($model, 'keterangan')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="text-right">
        <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function listJenisBarang(type)
    {
        $.ajax({
            url: "<?=Url::to(['barang/list']) ?>",
            type: "GET",
            data: {
                type: type
            },
            dataType: "text",
			error: function(xhr, status, error) {},
            beforeSend: function(){},
            success: function(data){
                var o = $.parseJSON(data);
                $("#masterbarang-jenis").empty();
				$.each(o, function(index, value){
					var opt = new Option(value.name, value.code, false, false);
					$("#masterbarang-jenis").append(opt);
				});
				$("#masterbarang-jenis").val(null);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){},
            complete: function(){}
        });
    }

    $(document).ready(function(){
        $("body").off("change","#masterbarang-type").on("change","#masterbarang-type", function(e){
            e.preventDefault();
            listJenisBarang($(this).val());
        });
    });
</script>