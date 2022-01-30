<table class="table table-bordered table-custom margin-top-10">
    <thead>
        <tr>
            <th class="text-center">No.</th>
            <th class="text-center">P x L</th>
            <th class="text-center">Total Potong</th>
            <th class="text-center">Total Objek</th>
            <th class="text-center">Total Warna</th>
            <th class="text-center">Lembar Ikat</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if(count($temps) > 0): ?>
            <?php foreach($temps as $index=>$val): ?>
                <tr>
                    <td class="text-center"><?=$index+1 ?></td>
                    <td class="text-center"><?=$val->panjang .'x'.$val->lebar ?></td>
                    <td class="text-right">
                        <?=$val->total_potong.'<br /><span class="text-muted font-size-10">(Jumlah cetak '.number_format($val['jumlah_cetak']).')</span>' ?>
                    </td>
                    <td class="text-right">
                        <?=$val->total_objek.'<br /><span class="text-muted font-size-10">(Jumlah objek '.number_format($val['jumlah_objek']).')</span>' ?>
                    </td>
                    <td class="text-center"><?=$val->total_warna ?></td>
                    <td>
                        <?=(!empty($val->lembar_ikat_1) ? number_format($val->lembar_ikat_1) .' '.$val->lembar_ikat_um_1 .' / ' : '') ?>
                        <?=(!empty($val->lembar_ikat_2) ? number_format($val->lembar_ikat_2) .' '.$val->lembar_ikat_um_2 .' / ' : '') ?>
                        <?=(!empty($val->lembar_ikat_3) ? number_format($val->lembar_ikat_3) .' '.$val->lembar_ikat_um_3 : '') ?>
                    </td>
                    <td class="text-center">
                        <a class="custom-btn btn btn-warning" href="javascript:void(0)" data-id="<?=$val->id ?>" data-button="update_temp">
                            <i class="fontello icon-pencil"></i>
                            <span>Update</span>
                        </a>
                        <a class="custom-btn btn btn-danger" href="javascript:void(0)" data-id="<?=$val->id ?>" data-button="delete_temp">
                            <i class="fontello icon-trash"></i>
                            <span>Hapus</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="text-danger" colspan="10">Data detail is empty</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>