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
foreach($dataProses as $proses=>$data){
    // print_r($data);
}
// die;
?>
<div class="spk-view">
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <?php if(count($model->details) > 0): ?>
            <?php foreach($model->details as $index=>$val): ?>
                <span class="font-bold">Status Produksi: </span><?=$model->statusProduksi() ?>
                <div class="margin-bottom-20"></div>
                <div class="document-container">
                    <div class="document-body">
                        <!-- DETAIL -->
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">Job</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold"><?=$val->order->name ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">Material</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold"><?=$val->item_code.' - '.$val->item->name ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">QTY Order</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold">
                                        <?php for($a=1;$a<3;$a++): ?>
                                            <?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).' '.$val['um_'.$a] : null ?>
                                        <?php endfor; ?>
                                    </span>
                                    <span class="text-muted font-size-12">
                                        <?='('.$val->stock->satuanTerkecil($val->item_code, [0=>$val->qty_order_1, 1=>$val->qty_order_2]).' LEMBAR)' ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">P x L</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold"><?=$val->panjang.' x '.$val->lebar ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">Total Potong</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold"><?=$val->total_potong.' <span class="text-muted font-size-10">(Jumlah cetak '.number_format($val['jumlah_cetak']).')</span>' ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">Total Objek</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold"><?=$val->total_objek.' <span class="text-muted font-size-10">(Jumlah objek '.number_format($val['jumlah_objek']).')</span>' ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">Total Warna</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold"><?=$val->total_warna ?></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <label class="font-size-12 margin-bottom-0">Lembar Ikat</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <span class="font-size-12">:</span>
                                    <span class="font-size-12 font-bold">
                                        <?=(!empty($val->lembar_ikat_1) ? number_format($val->lembar_ikat_1) .' '.$val->lembar_ikat_um_1 .' / ' : '') ?>
                                        <?=(!empty($val->lembar_ikat_2) ? number_format($val->lembar_ikat_2) .' '.$val->lembar_ikat_um_2 .' / ' : '') ?>
                                        <?=(!empty($val->lembar_ikat_3) ? number_format($val->lembar_ikat_3) .' '.$val->lembar_ikat_um_3 : '') ?>
                                    </span>
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
                        <!-- /DETAIL -->
                        <!-- DETAIL BAHAN -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <?php if(count($val->detailsBahan) > 0): ?>
                                <div class="margin-top-40"></div>
                                <h6 class="font-size-16"><strong>Detail Bahan:</strong></h6>
                                <ul class="custom-detail">
                                    <?php foreach($val->detailsBahan as $bahan): ?>
                                        <li>
                                            <span class="font-size-12">
                                                <?php if($model->status_produksi == 2): ?>
                                                    <i class="fontello icon-yelp"></i>
                                                <?php endif; ?>
                                                <?=
                                                    $bahan->item_bahan_name 
                                                        .' / '.((isset($bahan->typeBahan)) ? $bahan->typeBahan->name : '-') 
                                                        .' / <i class="text-muted">('.((($bahan->qty_1!=0) ? $bahan->qty_1 : 0).' '.$bahan->um_1.' / '.(($bahan->qty_2!=0) ? $bahan->qty_2 : 0).' '.$bahan->um_2).')</i>' 
                                                ?>
                                            </span>
                                            <?php if($model->status_produksi == 1): ?>
                                                <a class="text-danger"
                                                    href="javascript:void(0)" 
                                                    data-button="delete_bahan"
                                                    data-spk="<?=$bahan->no_spk?>"
                                                    data-urutan="<?=$bahan->urutan?>"
                                                    data-detail_urutan="<?=$bahan->detail_urutan?>"
                                                    data-item="<?=$bahan->item_code?>"
                                                    data-bahan="<?=$bahan->item_bahan_code?>"
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
                        <!-- /DETAIL BAHAN -->
                        <!-- DETAIL PROSES -->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <?php if(count($dataProses) > 0): ?>
                                <div class="margin-top-40"></div>
                                <h6 class="font-size-16"><strong>Detail Proses:</strong></h6>
                                <ul class="custom-detail padding-left-0">
                                    <?php foreach($dataProses as $namaProses=>$datas): ?>
                                        <div class="document-container">
                                            <div class="document-header"><?=$namaProses ?></div>
                                            <div class="document-body">
                                                <?php if(isset($datas[$val->urutan])): ?>
                                                    <?php foreach($datas[$val->urutan] as $data): ?>
                                                        <li class="document-li">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                    <label class="font-size-12">Mesin</label>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                    <span>:</span>
                                                                    <span class="font-size-12"><?=$data['nama_mesin'] ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                    <label class="font-size-12">Jenis Mesin</label>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                    <span>:</span>
                                                                    <span class="font-size-12"><?=$data['jenis_mesin'] ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                    <label class="font-size-12">QTY Proses</label>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                    <span>:</span>
                                                                    <span class="font-size-12"><i class="text-muted"><?=number_format($data['proses']->qty_proses) ?> Lembar Plano</i></span>
                                                                </div>
                                                                <?php if($model->status_produksi==2 && $data['proses']->status_proses==1): ?>
                                                                    <a class="text-danger" href="javascript:void(0)" data-button="delete_proses" data-spk="<?=$data['proses']->no_spk ?>" data-urutan="<?=$data['proses']->urutan ?>" data-detail_urutan="<?=$data['proses']->detail_urutan ?>" data-item="<?=$data['proses']->item_code ?>" title="Hapus Proses">
                                                                        <u>Hapus Proses</u>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php if($data['proses']->status_proses==3): ?>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                        <label class="font-size-12">Hasil Produksi</label>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                        <span>:</span>
                                                                        <span class="font-size-12"><i class="text-muted"><?=number_format($data['proses']->qty_hasil) ?> Lembar Plano</i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                        <label class="font-size-12">Status</label>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                        <span></span>
                                                                        <span></span>
                                                                        <?= $data['proses']->statusProses() ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if($data['proses']->status_proses==4): ?>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                        <label class="font-size-12">Hasil Produksi</label>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                        <span>:</span>
                                                                        <span class="font-size-12"><i class="text-muted"><?=number_format($data['proses']->qty_hasil) ?> Lembar Plano</i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                        <label class="font-size-12">Keterangan</label>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                        <span>:</span>
                                                                        <span class="font-size-12">
                                                                            <i class="text-muted"><?='Rusak '.number_format($data['proses']->qty_proses - $data['proses']->qty_hasil) ?> Lembar Plano</i>
                                                                            <span class="font-size-12 text-danger"><?=' ('.$data['proses']->keterangan.')'?></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                                    <div class="col-lg-2 col-md-2 col-xs-12  padding-left-0">
                                                                        <label class="font-size-12">Status</label>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0">
                                                                        <span></span>
                                                                        <span></span>
                                                                        <?= $data['proses']->statusProses() ?>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="text-danger">Proses masih kosong.</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <!-- /DETAIL PROSES -->
                        <!-- HASIL PRODUKSI -->
                        <?php if($model->status_produksi==3 && $data['proses']->status_proses==2): ?>
                            <div class="document-container">
                                <div class="document-header">Hasil Produksi</div>
                                <div class="document-body">
                                    <?php $form = ActiveForm::begin(['id'=>'produksi-'.$val->urutan]); ?>
                                        <?php foreach($dataProses as $namaProses=>$datas): ?>
                                            <div class="col-lg-12 col-md-12 col-xs-12">
                                                <h6><strong><u><?=$namaProses ?></u></strong></h6>
                                            </div>
                                            <div class="hidden">
                                                <?= $form->field($spkProses, 'no_spk[]')->hiddenInput(['value'=>$val->no_spk])->label(false) ?>
                                                <?= $form->field($spkProses, 'item_code[]')->hiddenInput(['value' => $val->item_code])->label(false) ?>
                                                <?= $form->field($spkProses, 'detail_urutan[]')->hiddenInput(['value' => $val->urutan])->label(false) ?>
                                            </div>
                                            <?php foreach($datas[$val->urutan] as $data): ?>
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <span class="font-size-12">QTY <?=$data['nama_mesin'] ?> :</span>
                                                        <?= $form->field($spkProses, 'qty_proses[]')->textInput([
                                                                'readonly' => true,
                                                                'value' => number_format($data['proses']->qty_proses),
                                                                'data-urutan' => $val->urutan,
                                                                'data-align' => 'text-right',
                                                            ])->label(false) ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <span class="font-size-12">Hasil <?=$data['nama_mesin'] ?> :</span>
                                                        <?= $form->field($spkProses, 'qty_hasil[]')->widget(MaskedInput::className(), [
                                                                'clientOptions' => [
                                                                    'alias' =>  'decimal',
                                                                    'groupSeparator' => ',',
                                                                    'autoGroup' => true,
                                                                ],
                                                                'options' => [
                                                                    'data-align' => 'text-right',
                                                                    'value' => number_format($data['proses']->qty_proses),
                                                                    'data-urutan' => $val->urutan,
                                                                ]
                                                            ])->label(false) ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                        <span class="font-size-12">Keterangan :</span>
                                                        <?= $form->field($spkProses, 'keterangan[]')->textarea([
                                                                'placeholder' => 'Masukkan keterangan',
                                                                'data-urutan' => $val->urutan
                                                            ])->label(false) ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                        <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                                            <p class="text-danger font-size-12">NB: Keterangan wajib diisi jika QTY hasil lebih kecil dari QTY Proses.</p>
                                            <button class="btn btn-success margin-bottom-20" data-button="update_proses" data-urutan="<?=$val->urutan?>">
                                                <i class="fontello icon-plus"></i>
                                                <span>Update Proses</span>
                                            </button>
                                        </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- /HASIL PRODUKSI -->
                        <!-- FORM -->
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                            <?php $form = ActiveForm::begin(['id'=>'form-'.$val->urutan]); ?>
                                <!-- Input Bahan -->
                                <?php if($model->status_produksi == 1): ?>
                                    <h6 class="font-bold font-size-16 margin-top-40">Input Bahan</h6>
                                    <hr />
                                    <div class="hidden">
                                        <?= $form->field($spkBahan, 'no_spk')->hiddenInput(['value'=>$model->no_spk])->label(false) ?>
                                        <?= $form->field($spkBahan, 'tgl_spk')->hiddenInput(['value'=>$model->tgl_spk])->label(false) ?>
                                        <?= $form->field($spkBahan, 'order_code')->hiddenInput(['value' => $val->order_code])->label(false) ?>
                                        <?= $form->field($spkBahan, 'detail_urutan')->hiddenInput(['value' => $val->urutan])->label(false) ?>
                                        <?= $form->field($spkBahan, 'item_bahan_code')->hiddenInput(['data-urutan' => $val->urutan])->label(false) ?>
                                        <?= $form->field($spkBahan, 'type_bahan_code')->hiddenInput(['data-urutan' => $val->urutan])->label(false) ?>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="font-size-12">Material:</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                            <?= $form->field($spkBahan, 'item_code')->textInput(['readonly' => true, 'value' => $spkDetail->item_code, 'data-urutan' => $val->urutan])->label(false) ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <?= $form->field($spkBahan, 'item_name')->textInput(['readonly' => true, 'value' => $spkDetail->item->name, 'data-urutan' => $val->urutan])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="font-size-12">Pilih Bahan:</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <?= $form->field($spkBahan, 'item_bahan_name')->textInput(['placeholder' => 'Pilih bahan tekan F4', 'data-urutan' => $val->urutan])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="font-size-12">Type Bahan:</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <?= $form->field($spkBahan, 'type_bahan')->textInput(['readonly' => true, 'data-urutan' => $val->urutan])->label(false) ?>
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
                                                        'readonly' => true,
                                                        'data-urutan' => $val->urutan,
                                                    ]
                                                ])->label(false) ?>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                                            <?= $form->field($spkBahan, 'um_1')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-urutan' => $val->urutan])->label(false) ?>
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
                                                        'data-urutan' => $val->urutan,
                                                    ]
                                                ])->label(false) ?>
                                        </div>
                                        <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 padding-left-0">
                                            <?= $form->field($spkBahan, 'um_2')->textInput(['readonly' => true, 'data-align' => 'text-right', 'data-urutan' => $val->urutan])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <button class="btn btn-success margin-bottom-20" data-urutan="<?=$val->urutan ?>" data-button="create_bahan" disabled>
                                            <i class="fontello icon-plus"></i>
                                            <span>Masukkan Bahan</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <!-- /Input Bahan -->
                                <!-- Input Proses -->
                                <?php if($model->status_produksi == 2): ?>
                                    <h6 class="font-bold font-size-16 margin-top-40">Input Proses</h6>
                                    <hr />
                                    <div class="hidden">
                                        <?= $form->field($spkProses, 'no_spk')->hiddenInput(['value'=>$model->no_spk])->label(false) ?>
                                        <?= $form->field($spkProses, 'order_code')->hiddenInput(['value' => $val->order_code])->label(false) ?>
                                        <?= $form->field($spkProses, 'detail_urutan')->hiddenInput(['value' => $val->urutan])->label(false) ?>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                <label class="font-size-12">Material:</label>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                                <?= $form->field($spkProses, 'item_code')->textInput(['readonly' => true, 'value' => $spkDetail->item_code, 'data-urutan' => $val->urutan])->label(false) ?>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                <?= $form->field($spkProses, 'item_name')->textInput(['readonly' => true, 'value' => $spkDetail->item->name, 'data-urutan' => $val->urutan])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="font-size-12">Pilih Proses:</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <?= $form->field($spkProses, 'type_proses['.$val->urutan.']')->widget(Select2::classname(), [
                                                    'data' => $model->prosesSpk(),
                                                    'options' => ['placeholder' => 'Pilih Proses', 'class' => 'select2', 'data-urutan' => $val->urutan],
                                                ])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="font-size-12">Pilih Mesin:</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                            <?= $form->field($spkProses, 'mesin_code['.$val->urutan.']')->widget(Select2::classname(), [
                                                    'data' => [],
                                                    'options' => ['placeholder' => 'Pilih Mesin', 'class' => 'select2', 'data-urutan' => $val->urutan],
                                                ])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                            <label class="font-size-12">Masukkan QTY yang akan di proses:</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                                            <?= $form->field($spkProses, 'qty_proses')->textInput(['data-align' => 'text-right', 'data-urutan' => $val->urutan])->label(false) ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                                            <p class="text-danger font-size-12" id="keterangan-proses-<?=$val->urutan?>"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <button class="btn btn-success margin-top-20 margin-bottom-20" data-urutan="<?=$val->urutan ?>" data-button="create_proses" disabled>
                                            <i class="fontello icon-plus"></i>
                                            <span>Tambah Proses SPK</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <!-- /Input Proses -->
                            <?php ActiveForm::end(); ?>
                        </div>
                        <!-- /FORM -->
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
    <?php if($model->status_produksi == 1): ?>
        <?= Html::a('<i class="fontello icon-progress-1"></i><span>Selesai Input Bahan kemudian Proses Input Mesin</span>', [
            'lock-bahan', 'no_spk'=>$model->no_spk], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
    <?php endif; ?>
    <?php if($model->status_produksi == 2): ?>
        <?= Html::a('<i class="fontello icon-progress-1"></i><span>Proses In Progress dan Input Hasil Produksi</span>', [
            'lock-proses', 'no_spk'=>$model->no_spk], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
    <?php endif; ?>
</div>
<div data-popup="popup"></div>
<script>
function load_bahan(urutan)
{
    $.ajax({
        url: "<?=Url::to(['spk-internal/list-bahan'])?>",
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
        complete: function(){
            $("#search").attr("data-urutan", urutan);
        }
    });
}

function search_bahan(code, urutan)
{
    $.ajax({
        url: "<?=Url::to(['spk-internal/search-bahan'])?>",
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
        complete: function(){
            $("#search").attr("data-urutan", urutan);
        }
    });
}

function item_bahan(code, urutan)
{
    $.ajax({
        url: "<?=Url::to(['spk-internal/item-bahan'])?>",
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
                $("#spkdetailbahan-"+index+"[data-urutan=\""+urutan+"\"]").val(value);
            });

            $("[id^=\"spkdetailbahan-qty_\"][data-urutan=\""+urutan+"\"]").attr("readonly", true);
            $("[id^=\"spkdetailbahan-qty_\"][data-urutan=\""+urutan+"\"]").val(null);
            for(var a=1;a<=o.composite;a++){
                $("#spkdetailbahan-qty_"+a+"[data-urutan=\""+urutan+"\"]").attr("readonly", false);
            }
            $("[data-button=\"create_bahan\"][data-urutan=\""+urutan+"\"]").prop("disabled", 0);
        },
        complete: function(){
            popup.close();
        }
    });
}

function create_bahan(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['spk-internal/create-bahan']) ?>",
        type: "POST",
        data: $("#form-"+data.urutan).serialize(),
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
        url: "<?= Url::to(['spk-internal/delete-bahan']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: {
            no_spk: data.spk,
            detail_urutan: data.detail_urutan,
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

function list_mesin(no_spk, item_code, urutan, type)
{
    $.ajax({
        url: "<?=Url::to(['spk-internal/list-mesin'])?>",
		type: "GET",
        data: {
            no_spk: no_spk,
            item_code: item_code,
            urutan: urutan,
            type: type,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#spkdetailproses-mesin_code-"+urutan).empty();
            $.each(o.mesin, function(index, value){
                var opt = new Option(value.name, value.code, false, false);
                $("#spkdetailproses-mesin_code-"+urutan).append(opt);
            });
            $("#spkdetailproses-mesin_code-"+urutan).val(null);

            $("#spkdetailproses-qty_proses[data-urutan=\""+urutan+"\"]").val(null);
            $("#spkdetailproses-qty_proses[data-urutan=\""+urutan+"\"]").val(o.sisa_proses);
            
            $("#keterangan-proses-"+urutan).empty();
            $("#keterangan-proses-"+urutan).text(o.keterangan);
            $("[data-button=\"create_proses\"][data-urutan=\""+urutan+"\"]").prop("disabled", 0);
        },
        complete: function(){}
    });
}

function create_proses(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['spk-internal/create-proses']) ?>",
        type: "POST",
        data: $("#form-"+data.urutan).serialize(),
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

function delete_proses(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['spk-internal/delete-proses']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: {
            no_spk: data.spk,
            urutan: data.urutan,
            detail_urutan: data.detail_urutan,
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

function hasil_produksi(el)
{
    data = el.data();
    $.ajax({
        url: "<?= Url::to(['spk-internal/hasil-produksi']) ?>",
        type: "POST",
        data: $("#produksi-"+data.urutan).serialize(),
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

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("keydown","#spkdetailbahan-item_bahan_name")
    $("body").on("keydown","#spkdetailbahan-item_bahan_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        var data = $(this).data();
        if(key == KEY.F4){
            load_bahan(data.urutan);
        }
    });
    $("body").off("click","[data-id=\"popup\"] table > tbody tr").on("click","[data-id=\"popup\"] table > tbody tr", function(e){
        e.preventDefault();
        var data = $(this).data(),
            urutan = $("#search").data().urutan;
        item_bahan(data.code, urutan);
    });

    $("body").off("click","[data-button=\"create_bahan\"]").on("click","[data-button=\"create_bahan\"]", function(e){
        e.preventDefault();
        create_bahan($(this));
    });
    $("body").off("click","[data-button=\"delete_bahan\"]").on("click","[data-button=\"delete_bahan\"]", function(e){
        e.preventDefault();
        delete_bahan($(this));
    });

    $("body").off("change","[id^=\"spkdetailproses-type_proses-\"]").on("change","[id^=\"spkdetailproses-type_proses-\"]", function(e){
        e.preventDefault();
        noSpk = $("#spkdetailproses-no_spk").val();
        itemCode = $("#spkdetailproses-item_code").val();
        data = $(this).data();
        list_mesin(noSpk, itemCode, data.urutan, $(this).val());
    });
    
    $("body").off("click","[data-button=\"create_proses\"]").on("click","[data-button=\"create_proses\"]", function(e){
        e.preventDefault();
        create_proses($(this));
    });
    $("body").off("click","[data-button=\"delete_proses\"]").on("click","[data-button=\"delete_proses\"]", function(e){
        e.preventDefault();
        delete_proses($(this));
    });

    $("body").off("click","[data-button=\"update_proses\"]").on("click","[data-button=\"update_proses\"]", function(e){
        e.preventDefault();
        hasil_produksi($(this));
    });
});
</script>