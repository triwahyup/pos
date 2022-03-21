<?php if(count($temps) > 0):  ?>
    <?php foreach($temps as $index=>$val):  ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="text-center"><?=$val->panjang ?></td>
            <td class="text-center"><?=$val->lebar ?></td>
            <td class="text-center"><?=$val->qty ?></td>
            <td class="text-center"><?=$val->panjang.' x '. $val->lebar ?></td>
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