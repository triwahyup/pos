<?php
$this->title = 'Barang: '.$model->barang->code .' - '.$model->barang->name;
$this->params['breadcrumbs'][] = ['label' => 'History In Out Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="stock-barang-view">
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">No.</th>
                    <th class="text-center" rowspan="2">Supplier</th>
                    <th class="text-center" rowspan="2">Type Bast</th>
                    <th class="text-center" rowspan="2">No. Bast</th>
                    <th class="text-center" rowspan="2">Tgl. Bast</th>
                    <th class="text-center" rowspan="2">Status</th>
                    <th class="text-center" colspan="2">QTY</th>
                    <th class="text-center" rowspan="2">Stock</th>
                </tr>
                <tr>
                    <th class="text-center">In</th>
                    <th class="text-center">Out</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($transaction) > 0): ?>
                    <?php foreach($transaction as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=($index+1)?></td>
                            <td><?=(isset($val->supplier)) ? $val->supplier->name : '' ?></td>
                            <td><?=$val->type_bast ?></td>
                            <td class="text-center"><?=$val->no_bast ?></td>
                            <td class="text-center"><?=date('d-m-Y', strtotime($val->tgl_bast)) ?></td>
                            <td class="text-center"><?=$val->status_bast ?></td>
                            <td class="text-right"><?=number_format($val->qty_bast) ?></td>
                            <td class="text-right"><?=number_format($val->qty_bast) ?></td>
                            <td class="text-right"><?=number_format($val->stock) ?></td>
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