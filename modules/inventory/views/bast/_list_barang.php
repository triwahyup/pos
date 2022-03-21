<?php use yii\helpers\Url; ?>
<input type="text" name="search" id="search" class="form-control" placeholder="Cari berdasarkan Kode dan Nama Barang ...">
<table class="table table-bordered table-custom margin-top-10">
	<thead>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">Kode Barang</th>
			<th class="text-center">Nama Barang</th>
			<th class="text-center">Um</th>
			<th class="text-center">Supplier</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($model) > 0): ?>
			<?php foreach($model as $index=>$val) : ?>
				<tr data-code="<?=$val->barang_code ?>">
                    <td class="text-center"><?=($index+1) ?></td>
                    <td class="text-center"><?=$val->barang_code ?></td>
                    <td><?=(isset($val->barang)) ? $val->barang->name : '-' ?></td>
                    <td class="text-center"><?=(isset($val->barang->satuan)) ? $val->barang->satuan->name : '-' ?></td>
                    <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                </tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-center text-danger" colspan="4"><i>Data is empty ...</i></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<script>
$(document).ready(function(){
	$("#search").autocomplete({
        minLength: 1,
        select: function(event, value){
			search_barang(value.item.code);
        },
        source: function(request, response){
            $.ajax({
                url: "<?=Url::to(['bast/autocomplete'])?>",
                type: "POST",
                dataType: "text",
                error: function(xhr, status, error) {},
                data: {
                    search: request.term,
                },
                beforeSend: function (data){
                    $("#search").loader("load");
                },
                success: function(data){
                    var o = $.parseJSON(data);
                    response(o);
                },
                complete: function(){
                    $("#search").loader("destroy");
                },
            });
        }
    });
});
</script>