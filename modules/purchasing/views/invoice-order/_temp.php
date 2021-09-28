<?php 
    $totalOrder=0;
    foreach($temps as $index=>$val): 
    $totalOrder += $val->total_order; ?>
    <tr>
        <td class="text-center"><?=$index+1?></td>
        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
        <?php for($a=1;$a<=3;$a++): ?>
            <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
        <?php endfor; ?>
        <?php for($a=1;$a<=3;$a++): ?>
            <td class="text-right"><?=(!empty($val['qty_terima_'.$a])) ? number_format($val['qty_terima_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
        <?php endfor; ?>
        <?php for($a=1;$a<=3;$a++): ?>
            <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
        <?php endfor; ?>
        <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
        <td class="text-right"><?=number_format($val->total_invoice).'.-' ?></td>
        <td class="text-center">
            <button class="btn btn-warning btn-xs btn-sm" data-invoice="<?=$val->no_invoice ?>" data-urutan="<?=$val->urutan ?>" data-button="update_temp">
                <i class="fontello icon-pencil"></i>
            </button>
        </td>
    </tr>
<?php endforeach; ?>