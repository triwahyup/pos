<?php if(count($temps) > 0): 
    $totalOrder=0; ?>
    <?php foreach($temps as $index=>$val): 
        $totalOrder += $val->total_order; ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
            <?php for($a=1;$a<=3;$a++): ?>
                <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <?php for($a=1;$a<=3;$a++): ?>
                <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
            <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
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
    <tr>
        <td class="text-right" colspan="9"><strong>Total Order:</strong></td>
        <td class="text-right"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
    </tr>
<?php else : ?>
    <tr>
        <td class="text-center text-danger" colspan="10">Data is empty</td>
    </tr>
<?php endif; ?>