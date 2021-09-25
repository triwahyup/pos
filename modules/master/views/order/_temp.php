<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $index=>$val): ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
            <td class="text-center"><?=$val->panjang.'x'.$val->lebar ?></td>
            <td class="text-center"><?=$val->potong.'/'.$val->objek ?></td>
            <td class="text-center"><?=$val->mesin.'/'.$val->jumlah_warna ?></td>
            <td class="text-right"><?='Rp.'.number_format($val->harga_jual) .'.- / Rp. '.number_format($val->harga_cetak).'.-' ?></td>
            <td class="text-center"><?=$val->lembar_ikat ?></td>
            <td class="text-center"><?=$val->min_order_ct.'/'.$val->min_order_lb ?></td>
            <td class="text-center">
                <button class="btn btn-warning btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="update_temp">
                    <i class="fontello icon-pencil"></i>
                </button>
                <button class="btn btn-danger btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="delete_temp">
                    <i class="fontello icon-trash"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else : ?>
    <tr>
        <td class="text-center text-danger" colspan="10">Data is empty</td>
    </tr>
<?php endif; ?>