<?php if(count($temps) > 0):  ?>
    <?php foreach($temps as $index=>$val):  ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td><?=$val->name ?></td>
            <td class="text-center"><?=$val->panjang.' x '. $val->lebar ?></td>
            <td class="text-right"><?=$val->gram .'<span class="text-muted"> Gram</span>' ?></td>
            <td class="text-right"><?=$val->qty .'<span class="text-muted"> Lembar</span>' ?></td>
            <td class="text-right"><?=(!empty($val->qty_sisa)) ? $val->qty_sisa : 0 ?></td>
            <td class="text-center">
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