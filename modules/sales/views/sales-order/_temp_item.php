<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $item): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <label class="font-size-12">Material</label>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 padding-right-0">
                <span><?=(isset($item->item->name)) ? $item->item->code.' - '.$item->item->name : '' ?></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right padding-right-0">
                <a class="custom-btn" href="javascript:void(0)" data-button="create_proses_temp" data-id="<?=$item->id ?>">
                    <i class="fontello icon-plus"></i>
                    <span>Add Proses Produksi</span>
                </a>
                <a class="custom-btn" href="javascript:void(0)" data-button="delete_temp" data-id="<?=$item->id ?>">
                    <i class="fontello icon-trash"></i>
                    <span>Hapus</span>
                </a>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
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
        <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
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
    <?php endforeach; ?>
<?php endif; ?>