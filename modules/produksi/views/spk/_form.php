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
            <!-- Detail Item -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <!-- Item Code -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Material</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <span class="font-size-12">
                                <strong>
                                    <?=$model->itemMaterial->item_code .' - '. $model->itemMaterial->item->name ?>
                                </strong>
                            </span>
                        </div>
                        <div class="hidden">
                            <?= $form->field($spkProduksi, 'no_spk')->hiddenInput(['value'=>$model->no_spk])->label(false) ?>
                            <?= $form->field($spkProduksi, 'item_code')->hiddenInput(['value'=>$model->itemMaterial->item_code])->label(false) ?>
                        </div>
                    </div>
                    <!-- QTY Order -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Qty Order</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong class="font-size-12">
                                <?php for($a=1;$a<3;$a++): ?>
                                    <?=(!empty($model->itemMaterial['qty_order_'.$a])) ? number_format($model->itemMaterial['qty_order_'.$a]).' '.$model->itemMaterial['um_'.$a] : null ?>
                                <?php endfor; ?>
                            </strong>
                            <span class="text-muted font-size-12">
                                <?='('.number_format($model->itemMaterial->inventoryStock->satuanTerkecil($model->itemMaterial->item_code, [0=>$model->itemMaterial->qty_order_1, 1=>$model->itemMaterial->qty_order_2])).' LEMBAR)' ?>
                            </span>
                        </div>
                    </div>
                    <!-- Up Produksi -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Up Produksi</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <?=$model->upProduksi ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Detail Item -->
            <!-- FORM -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-bottom-15">
                <hr class="hr-dashed" />
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-6 col-md-6 col-xs-12">    
                    <!-- Tgl. SPK -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Tgl SPK:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkProduksi, 'tgl_spk')->widget(DatePicker::classname(), [
                                'type' => DatePicker::TYPE_INPUT,
                                'options' => [
                                    'placeholder' => 'dd-mm-yyyy',
                                    'value' => (!$spkProduksi->isNewRecord) ? date('d-m-Y', strtotime($spkProduksi->tgl_spk)) : date('d-m-Y'),
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
                            <?= $form->field($spkProduksi, 'proses_code')->widget(Select2::classname(), [
                                    'data' => $so_proses,
                                    'options' => [
                                        'placeholder' => 'Pilih Proses Produksi',
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
                            <?= $form->field($spkProduksi, 'mesin_code')->widget(Select2::classname(), [
                                    'data' => [],
                                    'options' => [
                                        'placeholder' => 'Pilih Nama Mesin',
                                        'class' => 'select2',
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <?= $form->field($spkProduksi, 'urutan')->hiddenInput()->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <!-- Operator Mesin -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Operator Mesin:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkProduksi, 'user_id')->widget(Select2::classname(), [
                                    'data' => $operator,
                                    'options' => [
                                        'placeholder' => 'Pilih Operator Mesin',
                                        'class' => 'select2',
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                    <!-- Uk. Kertas -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Uk. Potong:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkProduksi, 'potong_id')->widget(Select2::classname(), [
                                    'data' => [],
                                    'options' => [
                                        'placeholder' => 'Pilih UK. Potong',
                                        'class' => 'select2',
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                    <?php if($model->status_produksi == 1): ?>
                        <!-- QTY Proses -->
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <label>QTY Proses:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkProduksi, 'qty_proses')->widget(MaskedInput::className(), [
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
                        <!-- Keterangan -->
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <label>Keterangan:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkProduksi, 'keterangan')->textarea(['rows'=>3])->label(false) ?>
                            </div>
                        </div>
                    <?php elseif($model->status_produksi == 2): ?>
                        <!-- QTY Hasil -->
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <label>QTY Hasil:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkProduksi, 'qty_hasil')->widget(MaskedInput::className(), [
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
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <?php if($model->status_produksi == 1): ?>
                    <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?= Html::a('<i class="fontello icon-floppy"></i><span>Simpan</span>', 'javascript:void(0)', [
                        'class' => 'btn btn-success btn-flat btn-sm hidden', 'data-button' => 'change']) ?>
                <?= Html::a('<i class="fontello icon-cancel"></i><span>Cancel</span>', [
                        'cancel-proses', 'no_spk' => $model->no_spk], [
                        'class' => 'btn btn-danger btn-flat btn-sm hidden', 'data-button' => 'cancel']) ?>
            </div>
            <!-- /FORM -->
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
                            <th class="text-center">Qty Proses (LB)</th>
                            <th class="text-center">Qty Hasil (LB)</th>
                            <th class="text-center">Qty Rusak (LB)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($model->produksiInAlls) > 0): ?>
                            <?php foreach($model->produksiInAlls as $index=>$val): ?>
                                <tr>
                                    <td class="text-center"><?=$index+1 ?></td>
                                    <td class="text-center"><?=date('d-m-Y', strtotime($val->tgl_spk)) ?></td>
                                    <td><?=(isset($val->proses)) ? $val->proses->name : '' ?></td>
                                    <td class="text-center"><?=$val->uk_potong ?></td>
                                    <td><?=(isset($val->mesin)) ? $val->mesin->name : '' ?></td>
                                    <td><?=(isset($val->operator)) ? $val->operator->name : '' ?></td>
                                    <td class="text-right"><?=number_format($val->qty_proses) ?></td>
                                    <td class="text-right"><?=number_format($val->qty_hasil) ?></td>
                                    <td class="text-right"><?=number_format($val->qty_rusak) ?></td>
                                    <td class="text-center"><?=$val->statusProduksi ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-xs btn-sm" 
                                            data-spk="<?=$val->no_spk ?>" 
                                            data-item="<?=$val->item_code ?>" 
                                            data-urutan="<?=$val->urutan ?>" 
                                            data-potong="<?=$val->potong_id ?>" 
                                            data-button="print">
                                            <i class="fontello icon-print"></i>
                                        </button>
                                        <button class="btn btn-warning btn-xs btn-sm" 
                                            data-spk="<?=$val->no_spk ?>" 
                                            data-item="<?=$val->item_code ?>" 
                                            data-urutan="<?=$val->urutan ?>" 
                                            data-potong="<?=$val->potong_id ?>" 
                                            data-button="update">
                                            <i class="fontello icon-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-xs btn-sm" 
                                            data-spk="<?=$val->no_spk ?>" 
                                            data-item="<?=$val->item_code ?>" 
                                            data-urutan="<?=$val->urutan ?>" 
                                            data-potong="<?=$val->potong_id ?>" 
                                            data-button="delete">
                                            <i class="fontello icon-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-danger" colspan="10">Data masih kosong.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- /Detail list proses -->
            <?php if($model->status_produksi == 1): ?>
                <div class="col-lg-12 col-md-12 col-xs-12 text-right margin-bottom-20">
                    <?= Html::a('<i class="fontello icon-arrows-cw"></i><span>Lanjut Proses Produksi</span>', ['post-proses', 'no_spk'=>$model->no_spk, 'type' => \Yii::$app->params['IN_PROGRESS']], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                </div>
            <?php elseif($model->status_produksi == 2): ?>
                <div class="col-lg-12 col-md-12 col-xs-12 text-right margin-bottom-20">
                    <?= Html::a('<i class="fontello icon-arrows-cw"></i><span>Input Proses Produksi</span>', ['post-proses', 'no_spk'=>$model->no_spk, 'type' => \Yii::$app->params['ON_START']], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="hidden" data-layout="print_layout"></div>
<script>
function load_mesin(code)
{
    $.ajax({
        url: "<?=Url::to(['spk/list-mesin'])?>",
		type: "GET",
        data: {
            code: code
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

function load_ukKertas(code, no_spk)
{
    $.ajax({
        url: "<?=Url::to(['spk/list-uk_kertas'])?>",
		type: "GET",
        data: {
            code: code,
            no_spk: no_spk
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkproduksi-potong_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.potong_id, false, false);
                $("#spkproduksi-potong_id").append(opt);
            });
            $("#spkproduksi-potong_id").val(null);
        },
        complete: function(){}
    });
}

function print_preview(data)
{
    $.ajax({
        url: "<?=Url::to(['spk/print-preview'])?>",
		type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            urutan: data.urutan,
            potong_id: data.potong
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (){},
        success: function(data){
            var o = $.parseJSON(data);
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
                w.print();
                w.close();
            });
        },
        complete: function(){}
    });
}

function get_proses(data)
{
    $.ajax({
        url: "<?=Url::to(['spk/get-proses'])?>",
		type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            urutan: data.urutan,
            potong_id: data.potong
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (){
            $("[data-button=\"change\"]").removeClass("hidden");
            $("[data-button=\"cancel\"]").removeClass("hidden");
            $("button[type=\"submit\"]").removeClass("hidden").addClass("hidden");
        },
        success: function(data){
            var o = $.parseJSON(data),
                mesin_code = '',
                potong_id = '';
            $.each(o, function(index, value){
                $("#spkproduksi-"+index).val(value).trigger("change");
                if(index == 'mesin_code')
                    mesin_code = value;
                if(index == 'potong_id')
                    potong_id = value;
            });
            setTimeout(function(){
                $("#spkproduksi-mesin_code").val(mesin_code).trigger("change");
                $("#spkproduksi-potong_id").val(potong_id).trigger("change");
            }, 600);
        },
        complete: function(){
            $("button:not([data-button=\"change\"]):not([data-button=\"cancel\"])").prop("disabled", true);
            $("#spkproduksi-qty_proses").attr("readonly", true);
        }
    });
}

function change_proses()
{
    $.ajax({
        url: "<?= Url::to(['spk/change-proses']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){},
        complete: function(){}
    });
}

function delete_proses(data)
{
    data = data.split("#");
    $.ajax({
        url: "<?= Url::to(['spk/delete-proses']) ?>",
        type: "GET",
        data: {
            no_spk: data[0],
            item_code: data[1],
            urutan: data[2],
            potong_id: data[3]
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){},
        complete: function(){}
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#spkproduksi-proses_code").on("change","#spkproduksi-proses_code", function(e){
        e.preventDefault();
        load_mesin($(this).val());
        var noSPK = $("#spkproduksi-no_spk").val();
        load_ukKertas($(this).val(), noSPK);
    });

    $("body").off("click","[data-button=\"print\"]").on("click","[data-button=\"print\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        print_preview(data);
    });

    $("body").off("click","[data-button=\"update\"]").on("click","[data-button=\"update\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        get_proses(data);
    });

    $("body").off("click","[data-button=\"change\"]").on("click","[data-button=\"change\"]", function(e){
        e.preventDefault();
        change_proses();
    });

    $("body").off("click","[data-button=\"delete\"]").on("click","[data-button=\"delete\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete",
			target: data.spk+"#"+data.item+"#"+data.urutan+"#"+data.potong
		});
    });
    $("body").off("click","#delete").on("click","#delete", function(e){
        e.preventDefault();
        delete_proses($(this).attr("data-target"));
    });
});
</script>