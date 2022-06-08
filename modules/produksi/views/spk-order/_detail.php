<!-- Detail list proses -->
<div class="col-lg-12 col-md-12 col-xs-12">
    <h4>Detail Proses</h4>
    <hr />
</div>
<div class="col-lg-12 col-md-12 col-xs-12">
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Proses</th>
                <th class="text-center">Urutan</th>
                <th class="text-center">Uk. Potong</th>
                <th class="text-center">Qty Proses</th>
                <th class="text-center">Qty Hasil</th>
                <th class="text-center">Qty Rusak</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dataProses as $index=>$val): ?>
                <tr>
                    <td class="text-center"><?=$index+1 ?></td>
                    <td><?=(isset($val->proses)) ? $val->proses->name : '' ?></td>
                    <td class="text-center"><?=$val->proses_id ?></td>
                    <td class="text-center"><?=(!empty($val->uk_potong)) ? $val->uk_potong : '-' ?></td>
                    <td class="text-right"><?=number_format($val->qty_proses).' LB' ?></td>
                    <td class="text-right"><?=number_format($val->qty_hasil).' LB' ?></td>
                    <td class="text-right"><?=number_format($val->qty_rusak).' LB' ?></td>
                    <td class="text-center"><?=$val->statusProduksi ?></td>
                    <td class="text-center">
                        <button class="btn btn-default" data-button="get_data"
                            data-spk="<?=$val->no_spk ?>" data-item="<?=$val->item_code ?>" data-id="<?=$val->proses_id ?>" data-mesin="<?=$val->mesin_type ?>">
                            <i class="fontello icon-pencil"></i>
                            <span>Atur Proses</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- /Detail list proses -->
<!-- Detail list history -->
<?php if(count($historyNotOutsource) > 0): ?>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <h4>Detail History Proses</h4>
        <hr />
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <table class="table table-bordered table-custom margin-top-10">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Tgl. SPK</th>
                    <th class="text-center">Proses</th>
                    <th class="text-center">Urutan</th>
                    <th class="text-center">Uk. Potong</th>
                    <th class="text-center">Mesin</th>
                    <th class="text-center">Operator</th>
                    <th class="text-center">Qty Proses</th>
                    <th class="text-center">Qty Hasil</th>
                    <th class="text-center">Qty Rusak</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($historyNotOutsource as $index=>$val): ?>
                    <tr>
                        <td class="text-center"><?=$index+1 ?></td>
                        <td class="text-center"><?=$val->tgl_spk ?></td>
                        <td><?=(isset($val->proses)) ? $val->proses->name : '' ?></td>
                        <td class="text-center"><?=$val->urutan ?></td>
                        <td class="text-center"><?=(!empty($val->uk_potong)) ? $val->uk_potong : '-' ?></td>
                        <td><?=(isset($val->mesin)) ? $val->mesin->name : '' ?></td>
                        <td><?=(isset($val->operator)) ? $val->operator->name : '' ?></td>
                        <td class="text-right"><?=number_format($val->qty_proses).' LB' ?></td>
                        <td class="text-right"><?=number_format($val->qty_hasil).' LB' ?></td>
                        <td class="text-right"><?=number_format($val->qty_rusak).' LB' ?></td>
                        <td class="text-center"><?=$val->statusProduksi ?></td>
                        <td class="text-center">
                            <?php if($val->status_produksi == 2): ?>
                                <button class="btn btn-warning btn-xs btn-sm" data-button="popup_input"
                                    data-spk="<?=$val->no_spk ?>" data-item="<?=$val->item_code ?>" data-id="<?=$val->proses_id ?>" data-urutan="<?=$val->urutan ?>">
                                    <i class="fontello icon-pencil"></i>
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-primary btn-xs btn-sm" data-button="print"
                                data-spk="<?=$val->no_spk ?>" data-item="<?=$val->item_code ?>" data-id="<?=$val->proses_id ?>" data-urutan="<?=$val->urutan ?>">
                                <i class="fontello icon-print"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<!-- /Detail list history -->