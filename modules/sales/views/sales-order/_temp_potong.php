<div class="col-lg-12 col-md-12 col-xs-12">
    <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
        <table class="table table-bordered table-custom margin-top-10">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">PxL</th>
                    <th class="text-center">Objek</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($temps as $no=>$val): ?>
                    <tr>
                        <td class="text-center"><?=$no+1 ?></td>
                        <td class="text-center"><?=$val->panjang.'x'.$val->lebar ?></td>
                        <td class="text-right">
                            <?=$val->objek .'
                                <span class="text-muted font-size-10">('.number_format($val->total_objek).' objek)</span>' ?>
                        </td>
                        <td class="text-center">
                            <a class="custom-btn" href="javascript:void(0)" data-button="delete_potong" data-id="<?=$val->id ?>">
                                <i class="fontello icon-trash"></i>
                                <span>Hapus</span>
                            </a>
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
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($tempsProses) > 0): ?>
                    <?php foreach($tempsProses as $no=>$tempProses): ?>
                        <tr>
                            <td class="text-center"><?=$no+1 ?></td>
                            <td class="text-muted text-left">
                                <?='<i>'.$tempProses->biayaProduksi->name.'</i>' ?>
                            </td>
                            <td class="text-muted text-left">
                                <?='<i>'.$tempProses->keterangan.'</i>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-danger" colspan="5">Data masih kosong.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>