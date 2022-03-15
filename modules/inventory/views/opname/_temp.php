<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $index=>$val): ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
            <?php for($a=1;$a<3;$a++): ?>
                <td class="text-right"><?=(!empty($val['qty_stock_'.$a])) ? number_format($val['qty_stock_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : '-' ?></td>
            <?php endfor; ?>
            <?php for($a=1;$a<3;$a++): ?>
                <td class="text-right"><?=(!empty($val['qty_'.$a])) ? number_format($val['qty_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <td class="text-right"><?=number_format($val->selisih) .'<br /><span class="text-muted font-size-10">'.$val->um_2.'</span>'?></td>
            <td class="text-center"><?=$val->statusBalance ?></td>
            <td><?=(!empty($val->keterangan)) ? '<span class="text-muted font-size-10">Keterangan: '.$val->keterangan.'</span>' : '' ?></td>
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