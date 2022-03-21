<?php use yii\helpers\Url; ?>
<input type="text" name="search" id="search" class="form-control" placeholder="Cari berdasarkan Kode dan Nama Material ...">
<table class="table table-bordered table-custom margin-top-10">
	<thead>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">Kode Item</th>
			<th class="text-center">Nama Item</th>
			<th class="text-center">Um</th>
			<th class="text-center">Supplier</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($model) > 0): ?>
			<?php foreach($model as $index=>$val) : ?>
				<tr data-code="<?=$val['item_code'] ?>">
                    <td class="text-center"><?=($index+1) ?></td>
                    <td class="text-center"><?=$val['item_code'] ?></td>
                    <td><?=$val['item_name'] ?></td>
                    <td class="text-center"><?=$val['satuan_name'] ?></td>
                    <td><?=$val['supplier_name'] ?></td>
                </tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-center text-danger" colspan="5"><i>Data is empty ...</i></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<script>
$(document).ready(function(){
	$("#search").autocomplete({
        minLength: 1,
        select: function(event, value){
			search_item(value.item.item_code);
        },
        source: function(request, response){
            $.ajax({
                url: "<?=Url::to(['potong-roll/autocomplete'])?>",
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