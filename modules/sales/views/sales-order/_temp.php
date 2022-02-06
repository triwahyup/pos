<?php
use yii\helpers\Html; ?>
<div class="col-lg-12 col-md-12 col-xs-12">
    <h4>Detail Material</h4>
    <hr />
</div>
<?php if(count($tempItemsMaterial) > 0): ?>
    <?php foreach($tempItemsMaterial as $item): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">Material</label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                <span><?=(isset($item->item->name)) ? $item->item->code.' - '.$item->item->name : '' ?></span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right padding-right-0">
                <a class="custom-btn" href="javascript:void(0)" data-button="delete_item_temp" data-id="<?=$item->id ?>">
                    <i class="fontello icon-trash"></i>
                    <span>Hapus</span>
                </a>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">QTY Order</label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                <strong class="font-size-12">
                    <?php for($a=1;$a<3;$a++): ?>
                        <?=(!empty($item['qty_order_'.$a])) ? number_format($item['qty_order_'.$a]).' '.$item['um_'.$a] : null ?>
                    <?php endfor; ?>
                </strong>
                <span class="text-muted font-size-12">
                    <?='('.number_format($item->inventoryStock->satuanTerkecil($item->item_code, [0=>$item->qty_order_1, 1=>$item->qty_order_2])).' LEMBAR)' ?>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">PxL</th>
                        <th class="text-center">Total Warna</th>
                        <th class="text-center">Total Potong</th>
                        <th class="text-center">Total Objek</th>
                        <th class="text-center">Lb. Ikat</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($item->details as $no=>$val): ?>
                        <tr>
                            <td class="text-center" rowspan="<?=count($val->prosesTemps)+1 ?>"><?=$no+1 ?></td>
                            <td class="text-center" rowspan="<?=count($val->prosesTemps)+1 ?>"><?=$val->panjang.'x'.$val->lebar ?></td>
                            <td class="text-center" rowspan="<?=count($val->prosesTemps)+1 ?>"><?=$val->total_warna ?></td>
                            <td class="text-right" rowspan="<?=count($val->prosesTemps)+1 ?>">
                                <?=$val->total_potong .'
                                    <span class="text-muted font-size-10">('.number_format($val->jumlah_cetak).' cetak)</span>' ?>
                            </td>
                            <td class="text-right" rowspan="<?=count($val->prosesTemps)+1 ?>">
                                <?=$val->total_objek .'
                                    <span class="text-muted font-size-10">('.number_format($val->jumlah_objek).' objek)</span>' ?>
                            </td>
                            <td class="text-right" rowspan="<?=count($val->prosesTemps)+1 ?>">
                                <span class="font-size-10">
                                    <?=(!empty($val->lembar_ikat_1) ? number_format($val->lembar_ikat_1) .' '.$val->lembar_ikat_um_1 .' / ' : '') ?>
                                    <?=(!empty($val->lembar_ikat_2) ? number_format($val->lembar_ikat_2) .' '.$val->lembar_ikat_um_2 .' / ' : '') ?>
                                    <?=(!empty($val->lembar_ikat_3) ? number_format($val->lembar_ikat_3) .' '.$val->lembar_ikat_um_3 : '') ?>
                                </strong>
                            </td>
                            <td class="text-center">
                                <a class="custom-btn" href="javascript:void(0)" data-button="create_proses_temp" data-id="<?=$val->id ?>">
                                    <i class="fontello icon-plus"></i>
                                    <span>Add Proses Produksi</span>
                                </a>
                                <a class="custom-btn" href="javascript:void(0)" data-button="delete_detail_temp" data-id="<?=$val->id ?>">
                                    <i class="fontello icon-trash"></i>
                                    <span>Hapus</span>
                                </a>
                            </td>
                        </tr>
                        <?php if(count($val->prosesTemps) > 0): ?>
                            <?php foreach($val->prosesTemps as $dataProses): ?>
                                <tr>
                                    <td class="text-muted text-left">
                                        <?='<i>'.$dataProses->biayaProduksi->name.'</i>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
    <h4>Detail Bahan Pembantu</h4>
    <hr />
</div>
<div class="col-lg-12 col-md-12 col-xs-12">
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="text-center" rowspan="2">No.</th>
                <th class="text-center" colspan="2">Item</th>
                <th class="text-center" colspan="3">QTY</th>
                <th class="text-center" rowspan="2">Jenis</th>
                <th class="text-center" rowspan="2">Action</th>
            </tr>
            <tr>
                <th class="text-center">Code</th>
                <th class="text-center">Name</th>
                <th class="text-center">1</th>
                <th class="text-center">2</th>
                <th class="text-center">3</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($tempItemsNonMaterial) > 0): ?>
                <?php foreach($tempItemsNonMaterial as $no=>$val): ?>
                    <tr>
                        <td class="text-center"><?=$no+1 ?></td>
                        <td class="text-center"><?=$val->item_code ?></td>
                        <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                        <?php for($a=1;$a<=3;$a++):?>
                            <td class="text-center"><?=($val['qty_order_'.$a] !=0) ? $val['qty_order_'.$a] .' '.$val['um_'.$a] : '' ?></td>
                        <?php endfor; ?>
                        <td class="text-center"><?=$val->item->material->name ?></td>
                        <td class="text-center">
                            <a class="custom-btn" href="javascript:void(0)" data-button="delete_item_temp" data-id="<?=$val->id ?>">
                                <i class="fontello icon-trash"></i>
                                <span>Hapus</span>
                            </a>
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
<div class="col-lg-12 col-md-12 col-xs-12">
    <div class="form-group text-right">
        <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
    </div>
</div>