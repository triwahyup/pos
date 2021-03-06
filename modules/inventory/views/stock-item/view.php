<?php
$this->title = 'Item: '.$model->item->code .' - '.$model->item->name;
$this->params['breadcrumbs'][] = ['label' => 'History In Out Material', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="stock-item-view">
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">No.</th>
                    <th class="text-center" rowspan="2">Supplier</th>
                    <th class="text-center" rowspan="2">Type Dokumen</th>
                    <th class="text-center" rowspan="2">No. Dokumen</th>
                    <th class="text-center" rowspan="2">Tgl. Dokumen</th>
                    <th class="text-center" rowspan="2">Status</th>
                    <th class="text-center" colspan="3">QTY</th>
                    <th class="text-center" rowspan="2">OnHand</th>
                    <th class="text-center" rowspan="2">OnSales</th>
                </tr>
                <tr>
                    <th class="text-center">In</th>
                    <th class="text-center">Out</th>
                    <th class="text-center">Retur</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($transaction) > 0): ?>
                    <?php foreach($transaction as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=($index+1)?></td>
                            <td><?=(isset($val->supplier)) ? $val->supplier->name : '' ?></td>
                            <td><?=$val->type_document ?></td>
                            <td class="text-center"><?=$val->no_document ?></td>
                            <td class="text-center"><?=date('d-m-Y', strtotime($val->tgl_document)) ?></td>
                            <td class="text-center"><?=$val->status_document ?></td>
                            <td class="text-right"><?='<strong>'.number_format($val->qty_in).'</strong><br /><span class="font-size-10">'. $val->konversi($val->item, $val->qty_in).'</span>' ?></td>
                            <td class="text-right"><?='<strong>'.number_format($val->qty_out).'</strong><br /><span class="font-size-10">'. $val->konversi($val->item, $val->qty_out).'</span>' ?></td>
                            <td class="text-right"><?='<strong>'.number_format($val->qty_retur).'</strong><br /><span class="font-size-10">'. $val->konversi($val->item, $val->qty_retur).'</span>' ?></td>
                            <td class="text-right"><?='<strong>'.number_format($val->onhand).'</strong><br /><span class="font-size-10">'. $val->konversi($val->item, $val->onhand).'</span>' ?></td>
                            <td class="text-right"><?='<strong>'.number_format($val->onsales).'</strong><br /><span class="font-size-10">'. $val->konversi($val->item, $val->onsales).'</span>' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center text-danger" colspan="15">Data is empty</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>