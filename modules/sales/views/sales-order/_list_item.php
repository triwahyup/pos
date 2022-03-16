<?php use yii\helpers\Url; ?>
<input type="text" name="search" id="search" class="form-control" placeholder="Cari berdasarkan Kode dan Nama Material ..." data-type="<?=$type ?>">
<table class="table table-bordered table-custom margin-top-10" data-table="master_item_material">
	<thead>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">Kode Item</th>
			<th class="text-center">Nama Item</th>
			<th class="text-center">Supplier</th>
			<th class="text-center">Type</th>
			<th class="text-center">UM</th>
			<th class="text-center">Stock</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($model) > 0): ?>
			<?php foreach($model as $index=>$val) : ?>
				<tr data-code="<?=$val['item_code'] ?>" data-supplier="<?=$val['supplier_code']?>" data-type="<?=$type ?>">
                    <td class="text-center"><?=($index+1) ?></td>
                    <td class="text-center"><?=$val['item_code'] ?></td>
                    <td><?=$val['item_name'] ?></td>
                    <td><?=$val['supplier_name'] ?></td>
                    <td><?=$val['type_name'] ?></td>
                    <td><?=$val['satuan_name'] ?></td>
                    <td><?=$val['stock'] ?></td>
                </tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr>
				<td class="text-center text-danger" colspan="8"><i>Data is empty ...</i></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<script>
var type = $("#search").data().type;
$(document).ready(function(){
	$("#search").autocomplete({
        minLength: 1,
        select: function(event, value){
            search_item(value.item.item_code, value.item.supplier_code, type);
        },
        source: function(request, response){
            $.ajax({
                url: "<?=Url::to(['sales-order/autocomplete-item'])?>",
                type: "POST",
                dataType: "text",
                error: function(xhr, status, error) {},
                data: {
                    search: request.term,
                    type: type,
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