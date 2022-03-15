<?php 
    $totalOrder=0;
    $totalInvoice=0;
    foreach($temps as $index=>$val): 
        $totalOrder += $val->total_order;
        $totalInvoice += $val->total_invoice; ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
            <td class="text-right"><?=number_format($val->qty_order_1).'<br /><span class="text-muted font-size-10">'.$val->um_1.'</span>' ?></td>
            <td class="text-right">
                <?php if($val->qty_selisih > 0): ?>
                    <?=number_format($val->qty_terima_1).' <span class="text-muted font-size-12">'.$val->um_1.'</span>' ?>
                    <br />
                    <strong class="text-danger">
                        <?='<i>Kurang '.number_format($val->qty_selisih).' '.$val->um_1.'</i>' ?>
                    </strong>
                <?php else: ?>
                    <?=number_format($val->qty_terima_1).'<br /><span class="text-muted font-size-10">'.$val->um_1.'</span>' ?>
                <?php endif; ?>
            </td>
            <td class="text-right"><?=number_format($val->harga_beli_1).'.- <br /><span class="text-muted font-size-10">Per '.$val->um_1.'</span>' ?></td>
            <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
            <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
            <td class="text-right"><?=number_format($val->total_invoice).'.-' ?></td>
            <td class="text-center">
                <?php if(empty($val->qty_terima_1) || $val->qty_selisih > 0): ?>
                    <button class="btn btn-warning btn-xs btn-sm" data-invoice="<?=$val->no_invoice ?>" data-urutan="<?=$val->urutan ?>" data-button="update_temp">
                        <i class="fontello icon-pencil"></i>
                    </button>
                <?php endif; ?>
            </td>
        </tr>
<?php endforeach; ?>
<tr>
    <td class="text-right summary" colspan="6"></td>
    <td class="text-right summary"><strong><?='Total Order: '.number_format($totalOrder).'.-' ?></strong></td>
    <td class="text-right summary"><strong><?='Total Invoice: '.number_format($totalInvoice).'.-' ?></strong></td>
</tr>