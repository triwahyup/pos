<table class="table table-bordered table-custom margin-top-10">
    <thead>
        <tr>
            <th class="text-center"></th>
            <th class="text-center">No.</th>
            <th class="text-center">Nama</th>
        </tr>
    </thead>
    <tbody>
		<?php if(count($data) > 0):
            $no=1; ?>
			<?php foreach($data as $val) : ?>
                <tr>
                    <td class="text-center">
                        <?php if(isset($val['id'])): ?>
                            <?php if($val['type'] == 1): ?>
                                <input type="checkbox" name="item" value="<?=$val['name'] ?>" id="proses_<?=$val['biaya'].'_'.$val['item'] ?>" data-id="<?=$val['id'] ?>" checked >
                            <?php else: ?>
                                <input type="checkbox" name="item" value="<?=$val['name'] ?>" id="proses_<?=$val['biaya'].'_'.$val['item'] ?>" data-id="<?=$val['id'] ?>" checked disabled >
                            <?php endif; ?>
                        <?php else: ?>
                            <input type="checkbox" name="item" value="<?=$val['name'] ?>" id="proses_<?=$val['biaya'].'_'.$val['item'] ?>">
                        <?php endif; ?>
                        <input type="hidden" id="so_<?=$val['biaya'].'_'.$val['item'] ?>" value="<?=$val['no_so'] ?>">
                        <input type="hidden" id="code_<?=$val['biaya'].'_'.$val['item'] ?>" value="<?=$val['order'] ?>">
                        <input type="hidden" id="biaya_<?=$val['biaya'].'_'.$val['item'] ?>" value="<?=$val['biaya'] ?>">
                        <input type="hidden" id="item_<?=$val['biaya'].'_'.$val['item'] ?>" value="<?=$val['item'] ?>">
                    </td>
                    <td class="text-center"><?=$no++ ?></td>
                    <td><?=$val['name'] ?></td>
                </tr>
            <?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-center text-danger" colspan="4"><i>Data is empty ...</i></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
    <button class="btn btn-primary" data-button="close">
        <i class="fontello icon-cancel"></i>
        <span>Close List Biaya</span>
    </button>
</div>