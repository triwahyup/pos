<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $no=>$val): ?>
        <tr>
            <td class="text-center"><?=$no+1 ?></td>
            <td class="text-center"><?=$val->item_code ?></td>
            <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
            <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
            <td class="text-center"><?=$val->qty_order_1.' '.$val->um_1 ?></td>
            <td class="text-center"><?=$val->item->material->name ?></td>
            <td class="text-center">
                <a class="custom-btn" href="javascript:void(0)" data-button="delete_temp" data-id="<?=$val->id ?>">
                    <i class="fontello icon-trash"></i>
                    <span>Hapus</span>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else : ?>
    <tr>
        <td class="text-center text-danger" colspan="8"><i>Data is empty ...</i></td>
    </tr>
<?php endif; ?>