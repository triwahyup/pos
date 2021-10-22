<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'No.SPK: '.$model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Spk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spk-view">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['id'=>'form']); ?>
            <div class="text-right">
                <a href="javascript:void(0)" id="hidden_detail_bahan">
                    <span>Tutup Form Input Bahan >></span>
                </a>
                <hr class="margin-top-5 margin-bottom-5" />
            </div>
            <div class="margin-bottom-40"></div>
            <div data-form>
                <!-- Material -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">Pilih Material:</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <?= $form->field($spkBahan, 'item_name')->widget(Select2::classname(), [
                                'data' => $dataItem,
                                'options' => [
                                    'placeholder' => 'Pilih Material',
                                    'class' => 'select2',
                                    'data-temp' => true
                                ],
                            ])->label(false) ?>
                        <?= $form->field($spkBahan, 'item_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">QTY Cetak / Objek:</label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($spkBahan, 'qty_cetak')->textInput(['readonly' => true, 'data-temp' => true])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <?= $form->field($spkBahan, 'qty_objek')->textInput(['readonly' => true, 'data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">Pilih Proses:</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <?= $form->field($spkBahan, 'pilih_proses')->widget(Select2::classname(), [
                                'data' => [],
                                'options' => [
                                    'placeholder' => 'Pilih Proses',
                                    'class' => 'select2',
                                    'data-temp' => true,
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">Pilih Mesin:</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <?= $form->field($spkBahan, 'pilih_mesin')->widget(Select2::classname(), [
                                'data' => [],
                                'options' => [
                                    'placeholder' => 'Pilih Mesin',
                                    'class' => 'select2',
                                    'data-temp' => true,
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Bahan -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="margin-top-40"></div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">Pilih Bahan:</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <?= $form->field($spkBahan, 'item_bahan_name')->textInput(['placeholder' => 'Pilih bahan tekan F4', 'data-temp' => true])->label(false) ?>
                        <?= $form->field($spkBahan, 'item_bahan_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">Type Bahan:</label>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <?= $form->field($spkBahan, 'type_bahan')->textInput(['readonly' => true, 'data-temp' => true])->label(false) ?>
                        <?= $form->field($spkBahan, 'type_bahan_code')->hiddenInput(['data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                        <label class="font-size-12">QTY Bahan:</label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 paddiing-left-0 padding-right-0">
                        <?= $form->field($spkBahan, 'qty_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($spkBahan, 'um_1')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-temp' => true])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <?= $form->field($spkBahan, 'qty_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                        <?= $form->field($spkBahan, 'um_2')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-temp' => true])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="margin-top-20"></div>
                    <button class="btn btn-success margin-bottom-20" data-button="create_temp">
                        <i class="fontello icon-plus"></i>
                        <span>Tambah Data Proses dan Bahan</span>
                    </button>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="text-right">
            <a href="javascript:void(0)" id="hidden_detail_material">
                <span>Tutup Detail Material dan Proses >></span>
            </a>
            <hr class="margin-top-5 margin-bottom-5" />
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <table class="table table-bordered table-custom" data-table="detail">
                <thead>
                    <tr>
                        <th class="text-center" width="40">No.</th>
                        <th class="text-center">Item</th>
                        <th class="text-center" colspan="2">QTY</th>
                        <th class="text-center" colspan="2">QTY Cetak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->details) > 0): ?>
                        <?php foreach($model->details as $index=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$index+1?></td>
                                <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                <?php for($a=1;$a<3;$a++): ?>
                                    <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                <?php endfor; ?>
                                <td class="text-right"><?=number_format($val['jumlah_cetak']).'.- <br /><span class="text-muted font-size-10">QTY Cetak</span>' ?></td>
                                <td class="text-right"><?=number_format($val['jumlah_objek']).'.- <br /><span class="text-muted font-size-10">QTY Objek</span>' ?></td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0 padding-right-0">
                                                    <div class="td-desc">
                                                        <label>Panjang</label>
                                                        <span><?=$val->panjang ?></span>
                                                    </div>
                                                    <div class="td-desc">
                                                        <label>Lebar</label>
                                                        <span><?=$val->lebar ?></span>
                                                    </div>
                                                    <div class="td-desc">
                                                        <label>Potong</label>
                                                        <span><?=$val->potong ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0 padding-right-0">
                                                    <div class="td-desc">
                                                        <label>Objek</label>
                                                        <span><?=$val->objek ?></span>
                                                    </div>
                                                    <div class="td-desc">
                                                        <label>Mesin</label>
                                                        <span><?=$val->mesin ?></span>
                                                    </div>
                                                    <div class="td-desc">
                                                        <label>Warna</label>
                                                        <span><?=$val->jumlah_warna ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0 padding-right-0">
                                                    <div class="td-desc">
                                                        <label>Lb. Ikat</label>
                                                        <span><?=$val->lembar_ikat ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <div class="col-lg-12 col-md-12 col-xs-12 text-left padding-left-0">
                                                <?php if(count($val->detailsProduksi) > 0):
                                                    $total_biaya=0;?>
                                                    <label class="text-left"><strong>Detail Proses:</strong></label>
                                                    <ul class="desc-custom padding-left-0">
                                                        <?php foreach($val->detailsProduksi as $v):
                                                            $total_biaya += $v->total_biaya;?>
                                                            <li>
                                                                <span><?=$v->name ?></span>
                                                            </li>
                                                        <?php endforeach; ?>
                                                        <li class="hidden"></li>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class="text-center text-danger" colspan="8">Data is empty</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="margin-bottom-20"></div>
    </div>
</div>
<div data-popup="popup"></div>
<script>
function list_proses(no_spk, code)
{
    $.ajax({
        url: "<?=Url::to(['spk/list-proses'])?>",
		type: "GET",
        data: {
            no_spk: no_spk,
            code: code
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkdetailbahan-pilih_proses").empty();
            $.each(o, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#spkdetailbahan-pilih_proses").append(opt);
            });
            $("#spkdetailbahan-pilih_proses").val(null);
        },
        complete: function(){}
    });
}

function event_change_item(el)
{
    no_spk = "<?=$_GET['no_spk']?>";
    code = el.val();
    $.ajax({
        url: "<?=Url::to(['spk/list-material'])?>",
		type: "GET",
        data: {
            no_spk: no_spk,
            code: code
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkdetailbahan-item_code").val(o.item_code);
            
            list_proses(no_spk, code);
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function load_bahan()
{
    $.ajax({
        url: "<?=Url::to(['spk/list-bahan'])?>",
		type: "GET",
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material Bahan',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function search_bahan(el)
{
    search = el.val();
    $.ajax({
        url: "<?=Url::to(['spk/search'])?>",
		type: "POST",
        data: {
            search: search,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){
			el.loader("load");
		},
        success: function(data){
            popup.close();
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material Bahan',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){
			el.loader("destroy");
		}
    });
}

function select_bahan(code)
{
    $.ajax({
        url: "<?=Url::to(['spk/item'])?>",
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
                $("#spkdetailbahan-"+index).val(value);
            });

            $("[id^=\"spkdetailbahan-qty_\"]").attr("readonly", true);
            $("[id^=\"spkdetailbahan-qty_\"]").val(null);
            for(var a=1;a<=o.composite;a++){
                $("#spkdetailbahan-qty_"+a).attr("readonly", false);
            }
        },
        complete: function(){
            popup.close();
        }
    });
}

$(document).ready(function(){
    $("body").off("click","#hidden_detail_bahan").on("click","#hidden_detail_bahan", function(e){
        e.preventDefault();
        $(this).parent().siblings("[data-form]").toggleClass("hidden");
        if($(this).parent().siblings("[data-form]").hasClass("hidden")){
            $(this).parent().siblings("[data-form]").slideUp();
            $(this).html("<span>Tampilkan Form Input Bahan >></span>");
        }else{
            $(this).parent().siblings("[data-form]").slideDown();
            $(this).html("<span>Tutup Form Input Bahan >></span>");
        }
    });

    $("body").off("click","#hidden_detail_material").on("click","#hidden_detail_material", function(e){
        e.preventDefault();
        $(this).parent().next().toggleClass("hidden");
        if($(this).parent().next().hasClass("hidden")){
            $(this).parent().next().slideUp();
            $(this).html("<span>Tampilkan Detail Material dan Proses >></span>");
        }else{
            $(this).parent().next().slideDown();
            $(this).html("<span>Tutup Detail Material dan Proses >></span>");
        }
    });

    $("body").off("change","#spkdetailbahan-item_name").on("change","#spkdetailbahan-item_name", function(e){
        e.preventDefault();
        event_change_item($(this));
    });

    $("body").off("change","#spkdetailbahan-pilih_proses").on("change","#spkdetailbahan-pilih_proses", function(e){
        e.preventDefault();
        // event_change_proses($(this));
    });

    $("body").off("keydown","#spkdetailbahan-item_bahan_name")
    $("body").on("keydown","#spkdetailbahan-item_bahan_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_bahan();
        }
    });
    $("body").off("keypress","#search").on("keypress","#search", function(e){
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if(key == KEY.ENTER){
            search_bahan($(this));
		}
	});
    $("body").off("click","[data-id=\"popup\"] table > tbody tr").on("click","[data-id=\"popup\"] table > tbody tr", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_bahan(data.code);
    });
});
</script>