<?php if(count($temps) > 0): ?>
    <?php foreach($temps as $index=>$val): ?>
        <tr>
            <td class="text-center"><?=$index+1?></td>
            <td class="text-center"><?=$val->urutan ?></td>
            <td><?=(isset($val->profile)) ? $val->profile->name : '-' ?></td>
            <td><?=(isset($val->typeUser)) ? $val->typeUser->name : '-' ?></td>
            <td class="text-center">
                <button class="btn btn-danger btn-xs btn-sm" data-id="<?=$val->id ?>" data-button="delete_temp">
                    <i class="fontello icon-trash"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else : ?>
    <tr>
        <td class="text-center text-danger" colspan="5">Data is empty</td>
    </tr>
<?php endif; ?>