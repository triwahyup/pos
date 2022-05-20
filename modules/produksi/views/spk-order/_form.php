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
                            <label class="margin-bottom-15">Material</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <span class="font-size-12">
                                <strong>
                                    <?=$dataItem['item_code'] .' - '. $dataItem['item_name'] ?>
                                </strong>
                            </span>
                        </div>
                        <div class="hidden">
                            <?= $form->field($spkDetail, 'no_spk')->hiddenInput(['value'=>$model->no_spk])->label(false) ?>
                            <?= $form->field($spkDetail, 'item_code')->hiddenInput(['value'=>$dataItem['item_code']])->label(false) ?>
                        </div>
                    </div>
                    <!-- QTY Order -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label class="margin-bottom-15">Qty Order</label>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong class="font-size-12">
                                <?=$dataItem['qty_order'] ?>
                            </strong>
                            <span class="text-muted font-size-12">
                                <?='('.$dataItem['qty_order_lb'].')' ?>
                            </span>
                        </div>
                    </div>
                    <!-- Up Produksi -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label class="margin-bottom-15">Up Produksi</label>
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
                            <?= $form->field($spkDetail, 'tgl_spk')->widget(DatePicker::classname(), [
                                'type' => DatePicker::TYPE_INPUT,
                                'options' => [
                                    'placeholder' => 'dd-mm-yyyy',
                                    'value' => (!$spkDetail->isNewRecord) ? date('d-m-Y', strtotime($spkDetail->tgl_spk)) : date('d-m-Y'),
                                ],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy',
                                ]])->label(false) ?>
                        </div>
                    </div>
                    <!-- Outsource -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Outsource:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkDetail, 'outsource_code')->widget(Select2::classname(), [
                                    'data' => $dataList['outsource'],
                                    'options' => [
                                        'placeholder' => 'Pilih Data Outsource',
                                        'class' => 'select2',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
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
                            <?= $form->field($spkDetail, 'mesin_code')->widget(Select2::classname(), [
                                    'data' => [],
                                    'options' => [
                                        'placeholder' => 'Pilih Nama Mesin',
                                        'class' => 'select2',
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <!-- Operator Mesin -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>Operator Mesin:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkDetail, 'user_id')->widget(Select2::classname(), [
                                    'data' => $dataList['operator'],
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
                            <?= $form->field($spkDetail, 'potong_id')->widget(Select2::classname(), [
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
                                <?= $form->field($spkDetail, 'qty_proses')->widget(MaskedInput::className(), [
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
                                <?= $form->field($spkDetail, 'keterangan')->textarea(['rows'=>3])->label(false) ?>
                            </div>
                        </div>
                    <?php elseif($model->status_produksi == 2): ?>
                        <!-- QTY Hasil -->
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <label>QTY Hasil:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkDetail, 'qty_hasil')->widget(MaskedInput::className(), [
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
                        <!-- QTY Rusak -->
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <label>QTY Rusak:</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkDetail, 'qty_rusak')->widget(MaskedInput::className(), [
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
                            <th class="text-center">Outsource</th>
                            <th class="text-center">Qty Proses</th>
                            <th class="text-center">Qty Hasil</th>
                            <th class="text-center">Qty Rusak</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($model->produksiInAlls) > 0): ?>
                            <?php foreach($model->produksiInAlls as $index=>$val): ?>
                                <tr>
                                    <td class="text-center"><?=$index+1 ?></td>
                                    <td class="text-center"><?=(!empty($val->tgl_spk)) ? date('d-m-Y', strtotime($val->tgl_spk)) : '-' ?></td>
                                    <td><?=(isset($val->proses)) ? $val->proses->name : '' ?></td>
                                    <td class="text-center"><?=(!empty($val->uk_potong)) ? $val->uk_potong : '-' ?></td>
                                    <td><?=(isset($val->mesin)) ? $val->mesin->name : '-' ?></td>
                                    <td><?=(isset($val->operator)) ? $val->operator->name : '-' ?></td>
                                    <td><?=(isset($val->outsource)) ? $val->outsource->name : '-' ?></td>
                                    <td class="text-right"><?=number_format($val->qty_proses).' LB' ?></td>
                                    <td class="text-right"><?=number_format($val->qty_hasil).' LB' ?></td>
                                    <td class="text-right"><?=number_format($val->qty_rusak).' LB' ?></td>
                                    <td class="text-center"><?=$val->statusProduksi ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-xs btn-sm"  data-spk="<?=$val->no_spk ?>" data-item="<?=$val->item_code ?>" data-urutan="<?=$val->urutan ?>" data-button="print">
                                            <i class="fontello icon-print"></i>
                                        </button>
                                        <button class="btn btn-warning btn-xs btn-sm" data-spk="<?=$val->no_spk ?>" data-item="<?=$val->item_code ?>" data-urutan="<?=$val->urutan ?>" data-button="update">
                                            <i class="fontello icon-pencil"></i>
                                        </button>
                                        <button class="btn btn-danger btn-xs btn-sm" data-spk="<?=$val->no_spk ?>" data-item="<?=$val->item_code ?>" data-urutan="<?=$val->urutan ?>" data-button="delete">
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
        url: "<?=Url::to(['spk-order/list-mesin'])?>",
		type: "GET",
        data: {
            code: code
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkorderdetail-mesin_code").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#spkorderdetail-mesin_code").append(opt);
            });
            $("#spkorderdetail-mesin_code").val(null);
        },
        complete: function(){}
    });
}

function load_ukKertas(code, no_spk)
{
    $.ajax({
        url: "<?=Url::to(['spk-order/list-uk_kertas'])?>",
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
            $("#spkorderdetail-potong_id").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.potong_id, false, false);
                $("#spkorderdetail-potong_id").append(opt);
            });
            $("#spkorderdetail-potong_id").val(null);
        },
        complete: function(){}
    });
}

function print_preview(data)
{
    $.ajax({
        url: "<?=Url::to(['spk-order/print-preview'])?>",
		type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            urutan: data.urutan
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
        url: "<?=Url::to(['spk-order/get-proses'])?>",
		type: "GET",
        data: {
            no_spk: data.spk,
            item_code: data.item,
            urutan: data.urutan,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (){},
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                $("[data-button=\"change\"]").removeClass("hidden");
                $("[data-button=\"cancel\"]").removeClass("hidden");
                $("button[type=\"submit\"]").removeClass("hidden").addClass("hidden");
                $("button:not([data-button=\"change\"]):not([data-button=\"cancel\"])").prop("disabled", true);
                $("#spkorderdetail-qty_proses").attr("readonly", true);
                
                var mesin_code = '',
                    potong_id = '',
                    qty_proses = 0;
                $.each(o.model, function(index, value){
                    $("#spkorderdetail-"+index).val(value).trigger("change");
                    if(index == 'mesin_code')
                        mesin_code = value;
                    if(index == 'potong_id')
                        potong_id = value;
                    if(index == 'qty_proses')
                        qty_proses = value;
                });
                setTimeout(function(){
                    $("#spkorderdetail-mesin_code").val(mesin_code).trigger("change");
                    $("#spkorderdetail-potong_id").val(potong_id).trigger("change");
                }, 600);
                $("#spkorderdetail-qty_hasil").val(qty_proses);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){}
    });
}

function change_proses()
{
    $.ajax({
        url: "<?= Url::to(['spk-order/change-proses']) ?>",
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
        url: "<?= Url::to(['spk-order/delete-proses']) ?>",
        type: "GET",
        data: {
            no_spk: data[0],
            item_code: data[1],
            urutan: data[2]
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
    $("body").off("change","#spkorderdetail-outsource_code").on("change","#spkorderdetail-outsource_code", function(e){
        e.preventDefault();
        $("#spkorderdetail-mesin_code").prop("disabled", true);
        $("#spkorderdetail-user_id").prop("disabled", true);
    }).bind("select2:clear", function(e){
        setTimeout(function(){
            $("#spkorderdetail-mesin_code").prop("disabled", false);
            $("#spkorderdetail-user_id").prop("disabled", false);
        }, 70);
    });

    $("body").off("change","#spkorderdetail-proses_code").on("change","#spkorderdetail-proses_code", function(e){
        e.preventDefault();
        load_mesin($(this).val());
        var noSPK = $("#spkorderdetail-no_spk").val();
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