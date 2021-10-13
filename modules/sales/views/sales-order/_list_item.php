<input type="text" name="search" id="search" class="form-control" placeholder="Cari berdasarkan Kode, Nama dan Type Material ...">
<table class="table table-bordered table-custom margin-top-10">
	<thead>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">Kode Order</th>
			<th class="text-center">Nama Job (Order)</th>
			<th class="text-center">Type Order</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($model) > 0): ?>
			<?php foreach($model as $index=>$val) : ?>
				<tr data-code="<?=$val->code ?>">
                    <td class="text-center"><?=($index+1) ?></td>
                    <td class="text-center"><?=$val->code ?></td>
                    <td><?=$val->name ?></td>
                    <td class="text-center"><?=($val->type_order==1) ? 'Produk' : 'Jasa' ?></td>
                </tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-center text-danger" colspan="4"><i>Data is empty ...</i></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>