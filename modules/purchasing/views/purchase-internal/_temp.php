<?php if(count($temps) > 0): 
    $totalOrder=0; ?>
    <?php foreach($temps as $index=>$val): 
        $totalOrder += $val->total_order; ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td><?=$val->item_name ?></td>
            <td class="text-right"><?=number_format($val->qty) ?></td>
            <td class="text-center"><?=$val->um ?></td>
            <td class="text-right"><?=number_format($val->harga_beli).'.-' ?></td>
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
        <td class="summary" colspan="6"><strong>Total Order:</strong></td>
        <td class="summary"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
    </tr>
<?php else : ?>
    <tr>
        <td class="text-center text-danger" colspan="10">Data is empty</td>
    </tr>
<?php endif; ?>