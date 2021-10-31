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
        <h4>Status Produksi:</h4>
        <?=$model->statusProduksi() ?>
    </div>
    <!-- DETAIL -->
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="text-right">
            <a href="javascript:void(0)" id="hidden_detail_material">
                <span>Detail Material dan Proses >></span>
            </a>
            <hr class="margin-top-5 margin-bottom-5" />
        </div>
        <div data-toggle>
            <?php if(count($model->details) > 0): ?>
                <?php foreach($model->details as $index=>$val): ?>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">Job</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12"><?=$val->order->name ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">Material</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12"><?=$val->item_code.' - '.$val->item->name ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">QTY Order</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12">
                                    <?php for($a=1;$a<3;$a++): ?>
                                        <?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).' '.$val['um_'.$a] : null ?>
                                    <?php endfor; ?>
                                </strong>
                                <span class="text-muted font-size-12">
                                    <?='('.$val->stock->satuanTerkecil($val->item_code, [0=>$val->qty_order_1, 1=>$val->qty_order_2]).' LEMBAR)' ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">P x L</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12"><?=$val->panjang.' x '.$val->lebar ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">Total Potong</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12"><?=$val->total_potong.' <span class="text-muted font-size-10">(Jumlah cetak '.number_format($val['jumlah_cetak']).')</span>' ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">Total Objek</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12"><?=$val->total_objek.' <span class="text-muted font-size-10">(Jumlah objek '.number_format($val['jumlah_objek']).')</span>' ?></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label class="font-size-12">Total Warna / Lb.Ikat</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                <span class="font-size-12">:</span>
                                <strong class="font-size-12"><?=$val->total_warna.' / '.$val->typeIkat ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <?php if(count($val->detailsProduksi) > 0):?>
                            <div class="col-lg-12 col-md-12 col-xs-12 text-left padding-left-0">
                                <strong class="font-size-12">Detail Proses:</strong>
                                <ul class="custom-detail">
                                    <?php foreach($val->detailsProduksi as $v):?>
                                        <li>
                                            <span class="font-size-12"><i class="fontello icon-ok"></i><?=$v->name ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- DETAIL BAHAN -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="margin-top-40"></div>
                        <?php if(count($val->detailsBahan) > 0): ?>
                            <h6 class="font-size-16"><strong>Detail Bahan:</strong></h6>
                            <ul class="custom-detail">
                                <?php foreach($val->detailsBahan as $v): ?>
                                    <li>
                                        <span class="font-size-12">
                                            <i class="fontello icon-yelp"></i>
                                            <?=
                                                $v->item_bahan_name 
                                                    .' / '.((isset($v->typeBahan)) ? $v->typeBahan->name : '-') 
                                                    .' / <i class="text-muted">('.((($v->qty_1!=0) ? $v->qty_1 : 0).' '.$v->um_1.' / '.(($v->qty_2!=0) ? $v->qty_2 : 0).' '.$v->um_2).')</i>' 
                                            ?>
                                        </span>
                                        <?php if($model->status_produksi == 1 && count($model->detailsProses) == 0): ?>
                                            <a class="text-danger"
                                                href="javascript:void(0)" 
                                                data-button="delete_bahan"
                                                data-spk="<?=$v->no_spk?>"
                                                data-urutan="<?=$v->urutan?>"
                                                data-item="<?=$v->item_code?>"
                                                data-bahan="<?=$v->item_bahan_code?>"
                                                title="Kembalikan Stock">
                                                <span>
                                                    <i class="fontello icon-cancel-circled"></i>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <!-- DETAIL PROSES -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="margin-top-40"></div>
                        <?php if(count($val->detailsProses) > 0): ?>
                            <h6 class="font-size-16"><strong>Proses Pengerjaan:</strong></h6>
                            <ul class="custom-detail">
                                <?php foreach($val->detailsProses as $v): ?>
                                    <li>
                                        <span class="font-size-12">
                                            <i class="fontello icon-progress-5 text-warning"></i>
                                            <?=
                                                $v->typeProses().' <i class="text-muted">(QTY: '.$v->qty_proses.')</i>'
                                                    .' / '.$v->mesin->name.' <i class="text-muted">('.$v->mesin->typeCode->value.')</i>'
                                            ?>
                                        </span>
                                        <?php if($model->status_produksi==2 && $v->status_proses==1): ?>
                                            <a class="text-danger"
                                                href="javascript:void(0)" 
                                                data-button="delete_proses"
                                                data-spk="<?=$v->no_spk?>"
                                                data-urutan="<?=$v->urutan?>"
                                                data-item="<?=$v->item_code?>"
                                                title="Hapus Proses">
                                                <span>
                                                    <i class="fontello icon-cancel-circled"></i>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                        <span><?=$v->statusProses() ?></span>
                                    </li>
                                    <li>
                                        <?php if($v->status_proses==3): ?>
                                            <span class="font-size-12 font-bold text-success"><?='Hasil Produksi: '.$v->qty_hasil ?></span>
                                        <?php endif; ?>
                                        <?php if($v->status_proses==4): ?>
                                            <?=
                                                '<span class="font-size-12 font-bold text-success">Hasil Produksi: '.$v->qty_hasil.', </span>
                                                <span class="font-size-12 font-bold text-danger">QTY Rusak: '.($v->qty_proses - $v->qty_hasil).'</span>'
                                            ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="margin-bottom-20"></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- FORM -->
    <div class="col-lg-12 col-md-12 col-xs-12">
        <?php $form = ActiveForm::begin(['id'=>'form']); ?>
            <div class="text-right">
                <a href="javascript:void(0)" id="hidden_detail_bahan">
                    <span>Form Input Bahan >></span>
                </a>
                <hr class="margin-top-5 margin-bottom-5" />
            </div>
            <div class="margin-bottom-40"></div>
            <div data-toggle>
                <!-- Bahan -->
                <?php if($model->status_produksi == 1): ?>
                    <?= $form->field($spkBahan, 'no_spk')->hiddenInput(['value'=>$_GET['no_spk']])->label(false) ?>
                    <?= $form->field($spkBahan, 'tgl_spk')->hiddenInput(['value'=>$model->tgl_spk])->label(false) ?>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="font-size-12">Material:</label>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($spkBahan, 'item_code')->textInput(['readonly' => true, 'value' => $spkDetail->item_code])->label(false) ?>
                            <?= $form->field($spkBahan, 'order_code')->hiddenInput(['readonly' => true, 'value' => $spkDetail->order_code])->label(false) ?>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <?= $form->field($spkBahan, 'item_name')->textInput(['readonly' => true, 'value' => $spkDetail->item->name])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="font-size-12">Pilih Bahan:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <?= $form->field($spkBahan, 'item_bahan_name')->textInput(['placeholder' => 'Pilih bahan tekan F4'])->label(false) ?>
                            <?= $form->field($spkBahan, 'item_bahan_code')->hiddenInput()->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <label class="font-size-12">Type Bahan:</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <?= $form->field($spkBahan, 'type_bahan')->textInput(['readonly' => true])->label(false) ?>
                            <?= $form->field($spkBahan, 'type_bahan_code')->hiddenInput()->label(false) ?>
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
                                    'options' => ['data-align' => 'text-right', 'readonly' => true]
                                ])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                            <?= $form->field($spkBahan, 'um_1')->textInput(['readonly' => true, 'data-align' => 'text-right'])->label(false) ?>
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
                                        'readonly' => true,
                                        'maxlength' => 3,
                                    ]
                                ])->label(false) ?>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                            <?= $form->field($spkBahan, 'um_2')->textInput(['readonly' => true, 'data-align' => 'text-right'])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="margin-top-20"></div>
                        <button class="btn btn-success margin-bottom-20" data-button="create_bahan">
                            <i class="fontello icon-plus"></i>
                            <span>Masukkan Bahan</span>
                        </button>
                    </div>
                <?php endif; ?>
                <!-- Material -->
                <?php if($model->status_produksi == 2): ?>
                    <?= $form->field($spkProses, 'no_spk')->hiddenInput(['value'=>$_GET['no_spk']])->label(false) ?>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0" data-proses>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <label class="font-size-12">Material:</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkProses, 'item_code')->textInput(['readonly' => true, 'value' => $spkDetail->item_code])->label(false) ?>
                                <?= $form->field($spkProses, 'order_code')->hiddenInput(['readonly' => true, 'value' => $spkDetail->order_code])->label(false) ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <?= $form->field($spkProses, 'item_name')->textInput(['readonly' => true, 'value' => $spkDetail->item->name])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <label class="font-size-12">Pilih Proses:</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <?= $form->field($spkProses, 'type_proses')->widget(Select2::classname(), [
                                        'data' => $model->prosesSpk(),
                                        'options' => ['placeholder' => 'Pilih Proses', 'class' => 'select2'],
                                    ])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <label class="font-size-12">Pilih Mesin:</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <?= $form->field($spkProses, 'mesin_code')->widget(Select2::classname(), [
                                        'data' => [],
                                        'options' => ['placeholder' => 'Pilih Mesin', 'class' => 'select2'],
                                    ])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                <label class="font-size-12">Masukkan QTY yang akan di proses:</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                <?= $form->field($spkProses, 'qty_proses')->textInput(['data-align' => 'text-right'])->label(false) ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                <p class="text-danger font-size-12" id="keterangan-proses"></p>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="margin-top-20"></div>
                            <button class="btn btn-success margin-bottom-20" data-button="create_proses">
                                <i class="fontello icon-plus"></i>
                                <span>Tambah Proses SPK</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 text-right">
    <?php if($model->status_produksi == 1): ?>
        <?= Html::a('<i class="fontello icon-progress-1"></i><span>Proses SPK</span>', [
            'lock-bahan', 'no_spk'=>$model->no_spk], ['class' => 'btn btn-primary btn-flat btn-sm margin-bottom-20']) ?>
    <?php endif; ?>
    <?php if($model->status_produksi == 2): ?>
        <?= Html::a('<i class="fontello icon-progress-1"></i><span>Proses In Progress SPK</span>', [
            'lock-proses', 'no_spk'=>$model->no_spk], ['class' => 'btn btn-primary btn-flat btn-sm margin-top-20']) ?>
    <?php endif; ?>
</div>
<div data-popup="popup"></div>
<script>
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

function search_bahan(code)
{
    $.ajax({
        url: "<?=Url::to(['spk/search-bahan'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
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
        complete: function(){}
    });
}

function item_bahan(code)
{
    $.ajax({
        url: "<?=Url::to(['spk/item-bahan'])?>",
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

function create_bahan(el)
{
    $.ajax({
        url: "<?= Url::to(['spk/create-bahan']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
                setTimeout(function(){
                    location.reload();
                }, 500);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function delete_bahan(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['spk/delete-bahan']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: {
            no_spk: data.spk,
            urutan: data.urutan,
            item_code: data.item,
            item_bahan_code: data.bahan,
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
                setTimeout(function(){
                    location.reload();
                }, 500);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function list_mesin(no_spk, item_code, type)
{
    $.ajax({
        url: "<?=Url::to(['spk/list-mesin'])?>",
		type: "GET",
        data: {
            no_spk: no_spk,
            item_code: item_code,
            type: type,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkdetailproses-mesin_code").empty();
            $.each(o.mesin, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#spkdetailproses-mesin_code").append(opt);
            });
            $("#spkdetailproses-mesin_code").val(null);

            $("#spkdetailproses-qty_proses").val(null);
            $("#spkdetailproses-qty_proses").val(o.sisa_proses);
            
            $("#keterangan-proses").empty();
            $("#keterangan-proses").text(o.keterangan);
        },
        complete: function(){}
    });
}

function create_proses(el)
{
    $.ajax({
        url: "<?= Url::to(['spk/create-proses']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: $("#form").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
                setTimeout(function(){
                    location.reload();
                }, 500);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function delete_proses(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['spk/delete-proses']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: {
            no_spk: data.spk,
            urutan: data.urutan,
            item_code: data.item,
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
                setTimeout(function(){
                    location.reload();
                }, 500);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("click","#hidden_detail_bahan").on("click","#hidden_detail_bahan", function(e){
        e.preventDefault();
        $(this).parent().siblings("[data-toggle]").toggleClass("hidden");
        if($(this).parent().siblings("[data-toggle]").hasClass("hidden")){
            $(this).parent().siblings("[data-toggle]").slideUp();
            $(this).html("<span>Form Input Bahan >></span>");
        }else{
            $(this).parent().siblings("[data-toggle]").slideDown();
            $(this).html("<span>Form Input Bahan >></span>");
        }
    });
    $("body").off("click","#hidden_detail_material").on("click","#hidden_detail_material", function(e){
        e.preventDefault();
        $(this).parent().siblings("[data-toggle]").toggleClass("hidden");
        if($(this).parent().siblings("[data-toggle]").hasClass("hidden")){
            $(this).parent().siblings("[data-toggle]").slideUp();
            $(this).html("<span>Detail Material dan Proses >></span>");
        }else{
            $(this).parent().siblings("[data-toggle]").slideDown();
            $(this).html("<span>Detail Material dan Proses >></span>");
        }
    });

    $("body").off("keydown","#spkdetailbahan-item_bahan_name")
    $("body").on("keydown","#spkdetailbahan-item_bahan_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_bahan();
        }
    });
    $("body").off("click","[data-id=\"popup\"] table > tbody tr").on("click","[data-id=\"popup\"] table > tbody tr", function(e){
        e.preventDefault();
        var data = $(this).data();
        item_bahan(data.code);
    });

    $("body").off("click","[data-button=\"create_bahan\"]").on("click","[data-button=\"create_bahan\"]", function(e){
        e.preventDefault();
        create_bahan($(this));
    });
    $("body").off("click","[data-button=\"delete_bahan\"]").on("click","[data-button=\"delete_bahan\"]", function(e){
        e.preventDefault();
        delete_bahan($(this));
    });

    $("body").off("change","#spkdetailproses-type_proses").on("change","#spkdetailproses-type_proses", function(e){
        e.preventDefault();
        noSpk = $("#spkdetailproses-no_spk").val();
        itemCode = $("#spkdetailproses-item_code").val();
        list_mesin(noSpk, itemCode, $(this).val());
    });

    $("body").off("click","[data-button=\"create_proses\"]").on("click","[data-button=\"create_proses\"]", function(e){
        e.preventDefault();
        create_proses($(this));
    });
    $("body").off("click","[data-button=\"delete_proses\"]").on("click","[data-button=\"delete_proses\"]", function(e){
        e.preventDefault();
        delete_proses($(this));
    });
});
</script>