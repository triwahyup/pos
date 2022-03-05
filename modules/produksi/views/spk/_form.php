<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spk-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Proses Produksi</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Item Code -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Material:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span class="font-size-12">
                            <strong>
                                <?=$model->itemMaterial->item_code .' - '. $model->itemMaterial->item->name ?>
                            </strong>
                        </span>
                        <?= $form->field($model, 'no_spk')->hiddenInput(['value'=>$model->no_spk])->label(false) ?>
                        <?= $form->field($model, 'item_code')->hiddenInput(['value'=>$model->itemMaterial->item_code])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 margin-bottom-5"></div>
                <!-- Uk. Kertas -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Uk. Potong:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'potong_id')->widget(Select2::classname(), [
                                'data' => $so_potong,
                                'options' => [
                                    'placeholder' => 'Pilih UK. Potong',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Tgl. SPK -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl SPK:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'tgl_spk')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'dd-mm-yyyy',
                                'value' => (!$spk_produksi->isNewRecord) ? date('d-m-Y', strtotime($spk_produksi->tgl_spk)) : date('d-m-Y'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]])->label(false) ?>
                    </div>
                </div>
                <!-- Proses Produksi -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Produksi:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'proses_code')->widget(Select2::classname(), [
                                'data' => $so_proses,
                                'options' => [
                                    'placeholder' => 'Pilih Proses Produksi',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Type Mesin -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tipe Mesin:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'mesin_type')->widget(Select2::classname(), [
                                'data' => $type_mesin,
                                'options' => [
                                    'placeholder' => 'Pilih Type Mesin',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Mesin Name -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Nama Mesin:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'mesin_code')->widget(Select2::classname(), [
                                'data' => [],
                                'options' => [
                                    'placeholder' => 'Pilih Nama Mesin',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Operator Mesin -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Operator Mesin:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'user_id')->widget(Select2::classname(), [
                                'data' => $operator,
                                'options' => [
                                    'placeholder' => 'Pilih Operator Mesin',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- QTY Proses -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>QTY Proses:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spk_produksi, 'qty_proses')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
            </div>
            <!-- Detail list proses -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <h4>Detail Proses</h4>
                <hr />
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <table class="table table-bordered table-custom margin-top-10">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Tgl. SPK</th>
                            <th class="text-center">Proses</th>
                            <th class="text-center">Uk. Potong</th>
                            <th class="text-center">Mesin</th>
                            <th class="text-center">Operator</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-danger" colspan="10">Data masih kosong.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /Detail list proses -->
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-layout="print_layout"></div>
<script>
function load_mesin(type)
{
    $.ajax({
        url: "<?=Url::to(['spk/list-mesin'])?>",
		type: "GET",
        data: {
            type: type
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkproduksi-mesin_code").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#spkproduksi-mesin_code").append(opt);
            });
            $("#spkproduksi-mesin_code").val(null);
        },
        complete: function(){}
    });
}

function print_preview()
{
    $.ajax({
        url: "<?=Url::to(['spk/print-preview'])?>",
		type: "POST",
        data: $("#print").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (){},
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                $("[data-layout=\"print_layout\"]").html(o.data)
                var w = window.open(),
                    newstr = $("[data-layout=\"print_layout\"]").html();
                $.get("css/print.min.css", function(css){
                    w.document.write("<html>");
                        w.document.write("<head>");
                            w.document.write("<style>");
                                w.document.write(css);
                            w.document.write("</style>");
                        w.document.write("</head>");
                        w.document.write("<body>");
                            $(w.document.body).html(newstr);
                        w.document.write("</body>");
                    w.document.write("</html>");
                });
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            popup.close();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#spkproduksi-mesin_type").on("change","#spkproduksi-mesin_type", function(e){
        e.preventDefault();
        load_mesin($(this).val());
    });

    $("body").off("click","[data-button=\"print\"]").on("click","[data-button=\"print\"]", function(e){
        e.preventDefault();
        print_preview();
    });
});
</script>