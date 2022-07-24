<div class="col-lg-12 col-md-12 col-xs-12">
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="font-size-10 text-center" rowspan="2">Item</th>
                <th class="font-size-10 text-center" rowspan="2">Qty</th>
                <th class="font-size-10 text-center" colspan="2">Harga (Rp)</th>
                <th class="font-size-10 text-center" colspan="2">Total Real Order (Rp)</th>
            </tr>
            <tr>
                <th class="font-size-10 text-center">Per RIM</th>
                <th class="font-size-10 text-center">Per LB</th>
                <th class="font-size-10 text-center">Material</th>
                <th class="font-size-10 text-center">Bahan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($model) > 0): 
                $totalOrder = 0;
                $grandTotal = 0; ?>
                <?php foreach($model as $val):
                    $totalOrder = $val->total_order_material;
                    $totalOrder += $val->total_order_bahan;
                    $grandTotal = $totalOrder; ?>
                    <?php foreach($val->itemsMaterial as $item): ?>
                        <tr>
                            <td class="font-size-10 text-left">
                                <?=(isset($item->item)) ? $item->item_code .' - '. $item->item->name : '-' ?>
                                <br />
                                <span class="font-size-10 text-muted"><?=(isset($item->supplier)) ? $item->supplier->name : '-' ?></span>
                            </td>
                            <td class="font-size-10 text-right"><?=(!empty($item->qty_order_1)) ? $item->qty_order_1 .' '. $item->um_1 : $item->qty_order_2 .' '. $item->um_2 ?></td>
                            <td class="font-size-10 text-right"><?=number_format($item->harga_jual_1).'.-' ?></td>
                            <td class="font-size-10 text-right"><?=number_format($item->harga_jual_2).'.-' ?></td>
                            <td class="font-size-10 text-right"><?=number_format($item->total_order).'.-' ?></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php foreach($val->itemsNonMaterial as $item): ?>
                        <tr>
                            <td class="font-size-10 text-left">
                                <?=(isset($item->item)) ? $item->item_code .' - '. $item->item->name.((isset($item->item->material)) ? ' - <i>'.$item->item->material->name.'</i>' : '') : '-' ?>
                                <br />
                                <span class="font-size-10 text-muted"><?=(isset($item->supplier)) ? $item->supplier->name : '-' ?></span>
                            </td>
                            <td class="font-size-10 text-right"><?=(!empty($item->qty_order_1)) ? $item->qty_order_1 .' '. $item->um_1 : $item->qty_order_2 .' '. $item->um_2 ?></td>
                            <td class="font-size-10 text-right"><?=number_format($item->harga_jual_1).'.-' ?></td>
                            <td class="font-size-10 text-right"><?=number_format($item->harga_jual_2).'.-' ?></td>
                            <td></td>
                            <td class="font-size-10 text-right"><?=number_format($item->total_order).'.-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td class="text-right mark" colspan="4">TOTAL:</td>
                        <td class="text-right mark"><?=number_format($val->total_order_material).'.-' ?></td>
                        <td class="text-right mark"><?=number_format($val->total_order_bahan).'.-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-right mark-2" colspan="5"><strong>GRAND TOTAL:</strong></td>
                        <td class="text-right mark-2"><strong><?=number_format($grandTotal).'.-' ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-danger" colspan="10">Data masih kosong.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>