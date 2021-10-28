<?php if(count($model) > 0): ?>
    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20"></div>
    <?php foreach($model as $val): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <span class="font-size-12">QTY <?=$val->typeProses().' ('.$val->mesin->name.')' ?>:</span>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <div class="form-group">
                    <input type="text" class="form-control text-right" id="qty_hasil_<?=$val->urutan?>" name="SpkDetailProses[qty_hasil][]" placeholder="Inputkan hasil produksi" value="<?=number_format($val->qty_proses) ?>" autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" value="<?=$val->urutan ?>" name="SpkDetailProses[urutan][]">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                <div class="form-group">
                    <textarea class="form-control" id="keterangan_<?=$val->urutan ?>" name="SpkDetailProses[keterangan][]" placeholder="Masukkan keterangan"></textarea>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="col-lg-6 col-md-6 col-xs-12 text-right padding-right-0">
        <p class="text-danger font-size-12">NB: Keterangan wajib diisi jika QTY hasil lebih kecil dari QTY Proses.</p>
        <button class="btn btn-success margin-bottom-20" data-button="update_proses">
            <i class="fontello icon-plus"></i>
            <span>Update Proses</span>
        </button>
    </div>
<?php else: ?>
    <div class="col-lg-6 col-md-6 col-xs-12 margin-top-20 text-center">
        <p class="text-danger">Belum ada proses produksi untuk dokumen SPK ini.</p>
    </div>
<?php endif; ?>