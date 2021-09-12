<?php 
    $totalOrder=0;
    foreach($temps as $index=>$val): 
    $totalOrder += $val->total_order; ?>
    <tr>
        <td class="text-center"><?=$index+1?></td>
        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
        <td class="text-center"><?=$val->satuan ?></td>
        <td class="text-right"><?=number_format($val->qty_order) ?></td>
        <td class="text-right"><?=number_format($val->qty_terima) ?></td>
        <td class="text-right"><?=number_format($val->harga_beli).'.-' ?></td>
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