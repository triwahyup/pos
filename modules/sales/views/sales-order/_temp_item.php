<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $no=>$val): ?>
        <tr>
            <td class="text-center"><?=$no+1 ?></td>
            <td class="text-center"><?=$val->item_code ?></td>
            <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
            <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
            <td class="text-right">
                <?=$val->qty_order_1 .' '. $val->um_1 ?>
                <br />
                <?=(!empty($val->qty_up)) ? '<i class="font-size-10 text-muted">Up '.$val->qty_up .' Lembar</i>': '' ?>
            </td>
            <td class="text-right">
                <strong>
                    <?=(!empty($val->lembar_ikat_1) ? number_format($val->lembar_ikat_1) .' '.$val->lembar_ikat_um_1 .' / ' : '') ?>
                    <?=(!empty($val->lembar_ikat_2) ? number_format($val->lembar_ikat_2) .' '.$val->lembar_ikat_um_2 .' / ' : '') ?>
                    <?=(!empty($val->lembar_ikat_3) ? number_format($val->lembar_ikat_3) .' '.$val->lembar_ikat_um_3 : '') ?>
                </strong>
            </td>
            <td class="text-center"><?=$val->total_potong ?></td>
            <td class="text-center"><?=$val->total_warna ?></td>
            <td class="text-center">
                <?php foreach($val->tempPotongs as $pt): ?>
                    <div class="border-custom">
                        <?='<span>'.$pt->panjang.'x'.$pt->lebar .'</span>' ?>
                    </div>
                <?php endforeach; ?>
            </td>
            <td class="text-center">
                <?php foreach($val->tempPotongs as $pt): ?>
                    <div class="border-custom">
                        <?='<span>'.$pt->objek .'</span>' ?>
                    </div>
                <?php endforeach; ?>
            </td>
            <td class="text-center">
                <?php foreach($val->tempPotongs as $pt): ?>
                    <div class="border-custom">
                        <a class="custom-btn" href="javascript:void(0)" data-button="delete_potong" data-id="<?=$pt->id ?>">
                            <i class="fontello icon-trash"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </td>
            <td class="text-center">
                <button class="btn btn-warning btn-xs btn-sm" data-button="update_temp" data-id="<?=$val->id ?>">
                    <i class="fontello icon-pencil"></i>
                </button>
                <button class="btn btn-danger btn-xs btn-sm" data-button="delete_temp" data-id="<?=$val->id ?>">
                    <i class="fontello icon-trash"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td class="text-center text-danger" colspan="15"><i>Data is empty ...</i></td>
    </tr>
<?php endif; ?>