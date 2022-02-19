<?php
$this->title = 'Nama Job: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-order-view">
    <div class="form-container no-background" render="detail">
        <div class="col-lg-12 col-md-12 col-xs-12 margin-bottom-20">
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0">
                    <h4>
                        <strong>
                            <u>Total Invoice</u>
                        </strong>
                    </h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Order Material</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->total_order_material).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Order Bahan</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->total_order_bahan).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Biaya Produksi</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->total_biaya_produksi).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>PPN (%)</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->total_ppn).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right margin-top-10">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label class="font-size-16">Grand Total</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-16"><?=number_format($model->grand_total).'.-' ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Job</h6>
            <hr />
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Nama Job</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->name ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>No. SO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->code ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. SO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->tgl_so)) ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Type Order</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->typeOrder ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>No. PO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->no_po ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. PO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->tgl_po)) ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Customer</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(isset($model->customer)) ? $model->customer->name : '' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Dateline</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->dateline)) ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Term In</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->term_in.' Hari' ?></span>
                    <div>
                        <i class="text-muted font-size-10">
                            <?='Tgl. Jatuh Tempo Pembayaran: '.date('d-m-Y', strtotime('+'.$model->term_in.' days', strtotime($model->tgl_so)))?>
                        </i>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Ekspedisi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->ekspedisi_name ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Up Produksi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->up_produksi)) ? $model->up_produksi.'%' : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>PPN</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->ppn)) ? $model->ppn.'%' : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Keterangan</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->keterangan ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Item</h6>
            <hr />
        </div>
        <?php foreach($model->itemsMaterial as $item): ?>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">Material</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=(isset($item->item->name)) ? $item->item->code.' - '.$item->item->name : '' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">QTY Order</label>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12">
                            <?php for($a=1;$a<3;$a++): ?>
                                <?=(!empty($item['qty_order_'.$a])) ? number_format($item['qty_order_'.$a]).' '.$item['um_'.$a] : null ?>
                            <?php endfor; ?>
                        </strong>
                        <span class="text-muted font-size-12">
                            <?='('.number_format($item->inventoryStock->satuanTerkecil($item->item_code, [0=>$item->qty_order_1, 1=>$item->qty_order_2])).' LEMBAR)' ?>
                        </span>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">Total Potong</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=$item->total_potong.'<span class="text-muted font-size-10"> ('.number_format($item->jumlah_cetak).' cetak)</span>' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">Lb. Ikat</label>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-right-0">
                        <span class="font-size-10">
                            <?=(!empty($item->lembar_ikat_1) ? number_format($item->lembar_ikat_1) .' '.$item->lembar_ikat_um_1 .' / ' : '') ?>
                            <?=(!empty($item->lembar_ikat_2) ? number_format($item->lembar_ikat_2) .' '.$item->lembar_ikat_um_2 .' / ' : '') ?>
                            <?=(!empty($item->lembar_ikat_3) ? number_format($item->lembar_ikat_3) .' '.$item->lembar_ikat_um_3 : '') ?>
                        </span>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">Total Warna</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=$item->total_warna ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">    
                        <label class="font-size-12">Harga</label>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12">
                            <?php for($a=1;$a<3;$a++): ?>
                                <?=(!empty($item['harga_jual_'.$a])) ? 
                                    '<span class="text-money">Rp.'.number_format($item['harga_jual_'.$a]).'.-</span>
                                    <span class="text-muted font-size-10">(Per Lembar '.$item['um_'.$a].')</span><br />' 
                                    : null ?>
                            <?php endfor; ?>
                        </strong>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">Total Order</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12"><?='Rp.'.number_format($item->total_order).'.-' ?></strong>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                        <table class="table table-bordered table-custom margin-top-10">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">PxL</th>
                                    <th class="text-center">Objek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($item->potongs as $no=>$val): ?>
                                    <tr>
                                        <td class="text-center"><?=$no+1 ?></td>
                                        <td class="text-center"><?=$val->panjang.'x'.$val->lebar ?></td>
                                        <td class="text-right">
                                            <?=$val->objek .'
                                            <span class="text-muted font-size-10">('.number_format($val->total_objek).' objek)</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 padding-right-0">
                        <table class="table table-bordered table-custom margin-top-10">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Proses Produksi</th>
                                    <th class="text-center">Index</th>
                                    <th class="text-center">Biaya Produksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $totalBiaya=0;
                                    foreach($item->proses as $no=>$val):
                                        $totalBiaya += $val->total_biaya; ?>
                                    <tr>
                                        <td class="text-center"><?=$no+1 ?></td>
                                        <td class="text-left text-muted">
                                            <?='<i>'.$val->biayaProduksi->name.'</i>' ?>
                                        </td>
                                        <td class="text-muted text-right">
                                            <?='<i>'.$val->index.'</i>' ?>
                                        </td>
                                        <td class="text-right">
                                            <?='<strong>'.number_format($val->total_biaya).'</strong>.-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="summary" colspan="3"><strong>Total Biaya:</strong></td>
                                    <td class="summary"><strong><?=number_format($totalBiaya).'.-' ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Bahan Pembantu</h6>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">No.</th>
                        <th class="text-center" colspan="2">Item</th>
                        <th class="text-center" colspan="2">QTY</th>
                        <th class="text-center" rowspan="2">Jenis</th>
                        <th class="text-center" colspan="2">Harga</th>
                        <th class="text-center" rowspan="2">Total Order</th>
                    </tr>
                    <tr>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Um 1</th>
                        <th class="text-center">Um 2</th>
                        <th class="text-center">Um 1</th>
                        <th class="text-center">Um 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsNonMaterial) > 0):
                        $totalOrder=0; ?>
                        <?php foreach($model->itemsNonMaterial as $no=>$val):
                            $totalOrder += $val->total_order; ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-center"><?=$val->item_code ?></td>
                                <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                                <?php for($a=1;$a<3;$a++):?>
                                    <td class="text-center"><?=($val['qty_order_'.$a] !=0) ? $val['qty_order_'.$a] .' '.$val['um_'.$a] : '' ?></td>
                                <?php endfor; ?>
                                <td class="text-center"><?=$val->item->material->name ?></td>
                                <?php for($a=1;$a<3;$a++):?>
                                    <td class="text-right"><?=($val['qty_order_'.$a] !=0) ? number_format($val['harga_jual_'.$a]).'.-' : '' ?></td>
                                <?php endfor; ?>
                                <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="summary" colspan="8"><strong>Total Order:</strong></td>
                            <td class="summary"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="10">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>