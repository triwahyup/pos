<?php if(count($temps) >0): ?>
    <?php foreach($temps as $index=>$val): ?>
        <tr>
            <td class="text-center"><?=($index +1)?></td>
            <td><?=$val->name ?></td>
            <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
            <?php for($a=1;$a<=3;$a++): ?>
                <td class="text-right"><?=(!empty($val['harga_beli_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <?php for($a=1;$a<=3;$a++): ?>
                <td class="text-right"><?=(!empty($val['harga_jual_'.$a])) ? number_format($val['harga_jual_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
            <?php endfor; ?>
            <td class="text-center"><?=$val->statusActive ?></td>
            <td class="text-center">
                <input type="radio" id="status_active_<?=$val->item_code.'-'.$val->urutan ?>" name="status_active_<?=$val->supplier_code ?>" data-id="<?=$val->id ?>" value="<?=$val->status_active ?>">
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
<?php else: ?>
    <tr>
        <td class="text-center text-danger" colspan="15">Data is empty</td>
    </tr>
<?php endif; ?>
