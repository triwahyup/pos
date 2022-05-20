<div class="col-lg-12 col-md-12 col-xs-12 margin-top-10 text-right">
    <button class="btn btn-default" data-code="<?=$code ?>" data-button="create_proses_temp">
        <i class="fontello icon-plus"></i>
        <span>Add Proses Produksi</span>
    </button>
</div>
<div class="col-lg-12 col-md-12 col-xs-12">
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Proses Produksi</th>
                <th class="text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($temps) > 0): ?>
                <?php foreach($temps as $no=>$temp): ?>
                    <tr>
                        <td class="text-center"><?=$no+1 ?></td>
                        <td class="text-muted text-left">
                            <?='<i>'.$temp->prosesProduksi->name.'</i>' ?>
                        </td>
                        <td class="text-muted text-left">
                            <?='<i>'.$temp->keterangan.'</i>' ?>
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