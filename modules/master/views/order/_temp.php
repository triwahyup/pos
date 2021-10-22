<?php if(count($temps) > 0): 
    $totalOrder = 0;
    $totalBiaya = 0; ?>
    <?php foreach($temps as $index=>$val): 
        $totalOrder += $val->total_order; ?>
        <tr>
            <td class="text-center" rowspan="2"><?=$index+1?></td>
            <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
            <?php for($a=1;$a<3;$a++): ?>
                <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <?php for($a=1;$a<3;$a++): ?>
                <td class="text-right"><?=(!empty($val['harga_jual_'.$a])) ? number_format($val['harga_jual_'.$a]).'.-<br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <td class="text-right"><?=number_format($val['jumlah_cetak']).'.- <br /><span class="text-muted font-size-10">QTY Cetak</span>' ?></td>
            <td class="text-right"><?=number_format($val['jumlah_objek']).'.- <br /><span class="text-muted font-size-10">QTY Objek</span>' ?></td>
            <td class="text-right"><?=number_format($val['harga_cetak']).'.- <br /><span class="text-muted font-size-10">Per Objek</span>' ?></td>
            <td class="text-center">
                <button class="btn btn-warning btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="update_temp">
                    <i class="fontello icon-pencil"></i>
                </button>
                <button class="btn btn-danger btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="delete_temp">
                    <i class="fontello icon-trash"></i>
                </button>
            </td>
        </tr>
        <tr>
            <td colspan="10">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                    <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
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
                                <div class="td-desc">
                                    <a id="tambah_proses_<?=$val->item_code ?>" href="javascript:void(0)">Tambah Proses</a>
                                    <ul class="option-custom">
                                        <?php foreach($biaya as $v): ?>
                                            <li>
                                                <?=$v->name?>
                                                <input type="hidden" name="biaya" id="biaya" value="<?=$v->code ?>">
                                                <input type="hidden" name="item" id="item" value="<?=$val->item_code ?>">
                                                <input type="hidden" name="code" id="code" value="<?=$val->order_code ?>">
                                            </li>
                                        <?php endforeach; ?>
                                        <li data-event="close">
                                            <a class="close" href="javascript:void(0)">Tutup</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                        <div class="col-lg-12 col-md-12 col-xs-12 text-left padding-left-0">
                            <?php if(count($val->detailsProduksi) > 0):
                                $total_biaya=0;?>
                                <label class="text-left">Detail Proses:</label>
                                <ul class="desc-custom padding-left-0">
                                    <?php foreach($val->detailsProduksi as $v):
                                        $total_biaya += $v->total_biaya;
                                        $totalBiaya += $v->total_biaya; ?>
                                        <li>
                                            <span><?=$v->name ?></span>
                                            <span><?='Rp. '.number_format($v->total_biaya).'.-' ?></span>
                                            <span id="delete_temp" data-id="<?=$v->id ?>">
                                                <i class="fontello icon-cancel-circled"></i>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                    <li>
                                        <span class="text-right"><strong>Total Biaya:</strong></span>
                                        <span><?='Rp. '.number_format($total_biaya).'.-' ?></span>
                                    </li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td class="summary" colspan="5"></td>
        <td class="summary" colspan="2"><strong><?='Total Order: Rp. '.number_format($totalOrder).'.-' ?></strong></td>
        <td class="summary" colspan="2"><strong><?='Total Biaya: Rp. '.number_format($totalBiaya).'.-' ?></strong></td>
        <td class="summary"><strong><?='Grand Total: Rp. '.number_format($totalOrder+$totalBiaya).'.-' ?></strong></td>
    </tr>
<?php else : ?>
    <tr>
        <td class="text-center text-danger" colspan="10">Data is empty</td>
    </tr>
<?php endif; ?>