<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $index=>$val): ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="text-center"><?=$val->item_code ?></td>
            <td><?=$val->item->name ?></td>
            <td><?=(isset($val->supplier)) ? $val->supplier->name : '' ?></td>
            <td class="text-right">
                <?=(!empty($val->qty_order_1)) 
                    ? number_format($val->qty_order_1).' '.$val->um_1 
                    : number_format($val->qty_order_2).' '.$val->um_2 
                ?>
            </td>
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