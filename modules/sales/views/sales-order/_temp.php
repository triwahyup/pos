<?php if(count($temps) > 0): 
    $totalOrder = 0;
    $totalBiaya = 0; ?>
    <?php foreach($temps as $index=>$val): 
        $totalOrder += $val->total_order; ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0" data-form="detail">
            <!-- Detail Material -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">Material</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <span class="font-size-12"><?=$val->item_code.' - '.$val->item->name ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">QTY Order</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12">
                            <?php for($a=1;$a<3;$a++): ?>
                                <?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).' '.$val['um_'.$a] : null ?>
                            <?php endfor; ?>
                        </strong>
                        <span class="text-muted font-size-12">
                            <?='('.$val->inventoryStock->satuanTerkecil($val->item_code, [0=>$val->qty_order_1, 1=>$val->qty_order_2]).' LEMBAR)' ?>
                        </span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0 margin-bottom-20">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">Harga Jual (Rp)</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12">
                            <?php for($a=1;$a<3;$a++): ?>
                                <?=(!empty($val['harga_jual_'.$a])) ? 
                                    'Rp.'.number_format($val['harga_jual_'.$a]).'.-
                                    <span class="text-muted font-size-10">(Per '.$val['um_'.$a].')</span><br />' 
                                    : null ?>
                            <?php endfor; ?>
                        </strong>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">P x L</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12"><?=$val->panjang.' x '.$val->lebar ?></strong>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">Total Potong</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12"><?=$val->total_potong.' <span class="text-muted font-size-10">(Jumlah cetak '.number_format($val['jumlah_cetak']).')</span>' ?></strong>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">Total Objek</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12"><?=$val->total_objek.' <span class="text-muted font-size-10">(Jumlah objek '.number_format($val['jumlah_objek']).')</span>' ?></strong>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        <label class="font-size-12">Total Warna / Lb.Ikat</label>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12"><?=$val->total_warna.' / '.$val->typeIkat ?></strong>
                    </div>
                </div>
            </div>
            <!-- /Detail Material -->
            <!-- Detail Proses -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-12 col-md-12 col-xs-12 text-left padding-left-0">
                    <?php foreach($biaya as $v): ?>
                        <input type="checkbox" name="item" value="<?=$v->name ?>" id="proses_<?=$v->code.'_'.$val->item_code ?>" data-type="pizza" data-title="<?=$v->name ?>">
                        <input type="hidden" name="biaya" id="biaya_<?=$v->code.'_'.$val->item_code ?>" value="<?=$v->code ?>">
                        <input type="hidden" name="item" id="item_<?=$v->code.'_'.$val->item_code ?>" value="<?=$val->item_code ?>">
                        <input type="hidden" name="code" id="code_<?=$v->code.'_'.$val->item_code ?>" value="<?=$val->order_code ?>">
                    <?php endforeach; ?>
                    <hr />
                    <?php if(count($val->detailsProduksi) > 0):
                        $total_biaya=0;?>
                        <ul class="text-right">
                            <?php foreach($val->detailsProduksi as $v):
                                $total_biaya += $v->total_biaya;
                                $totalBiaya += $v->total_biaya; ?>
                                <li>
                                    <span class="label"><?=$v->name ?></span>
                                    <span class="currency"><?='Rp. '.number_format($v->total_biaya).'.-' ?></span>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <span class="label text-right">Total Biaya</span>
                                <span class="currency summary"><?='Rp. '.number_format($total_biaya).'.-' ?></span>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /Detail Proses -->
        </div>
    <?php endforeach; ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0" data-form="summary">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <span class="text-right"><?='Total Material: Rp. '.number_format($totalOrder).'.-' ?></span>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <span class="text-right"><?='Total Biaya: Rp. '.number_format($totalBiaya).'.-' ?></span>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <span class="text-right"><?='Grand Total: Rp. '.number_format($totalOrder+$totalBiaya).'.-' ?></span>
        </div>
    </div>
<?php endif; ?>