<?php use yii\helpers\Url; ?>
<input type="text" name="search" id="search" class="form-control" placeholder="Cari berdasarkan Kode dan Nama Barang ...">
<table class="table table-bordered table-custom margin-top-10">
	<thead>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">Kode Barang</th>
			<th class="text-center">Nama Barang</th>
			<th class="text-center">Um</th>
		</tr>
	</thead>
	<tbody>
		<?php if(count($model) > 0): ?>
			<?php foreach($model as $index=>$val) : ?>
				<tr data-code="<?=$val->code ?>">
                    <td class="text-center"><?=($index+1) ?></td>
                    <td class="text-center"><?=$val->code ?></td>
                    <td><?=$val->name ?></td>
                    <td class="text-center"><?=(isset($val->satuan)) ? $val->satuan->name : '' ?></td>
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
    var supplier_code = $("#purchaseinternal-supplier_code").val(),
        type_barang = $("#purchaseinternal-type_barang").val()
	$("#search").autocomplete({
        minLength: 1,
        select: function(event, value){
			search_barang(value.item.code);
        },
        source: function(request, response){
            $.ajax({
                url: "<?=Url::to(['purchase-internal/autocomplete'])?>",
                type: "POST",
                dataType: "text",
                error: function(xhr, status, error) {},
                data: {
                    search: request.term,
                    supplier_code: supplier_code,
                    type_barang: type_barang
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