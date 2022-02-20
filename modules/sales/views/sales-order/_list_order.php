<?php use yii\helpers\Url; ?>
<input type="text" name="search" id="search" class="form-control" placeholder="Cari berdasarkan Nama Job ...">
<table class="table table-bordered table-custom margin-top-10" data-table="sales_order">
    <thead>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">No. SO</th>
			<th class="text-center">Tgl. SO</th>
			<th class="text-center">Nama Job</th>
			<th class="text-center">Customer</th>
		</tr>
	</thead>
    <tbody>
		<?php if(count($model) > 0): ?>
			<?php foreach($model as $index=>$val) : ?>
				<tr data-code="<?=$val->code ?>">
                    <td class="text-center"><?=($index+1) ?></td>
                    <td class="text-center"><?=$val->code ?></td>
                    <td class="text-center"><?=date('d-m-Y', strtotime($val->tgl_so)) ?></td>
                    <td><?=$val->name ?></td>
                    <td><?=(isset($val->customer)) ? $val->customer->name : '' ?></td>
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
			search_order(value.item.code);
        },
        source: function(request, response){
            $.ajax({
                url: "<?=Url::to(['sales-order/autocomplete-order'])?>",
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