<!-- Detail list proses -->
<div class="col-lg-12 col-md-12 col-xs-12">
    <h4>Detail Proses</h4>
    <hr />
</div>
<div class="col-lg-12 col-md-12 col-xs-12">
    <?php foreach($dataProses as $supplierName=>$listProses): ?>
        <strong class="font-size-14"><?='Supplier: '.$supplierName ?></strong>
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
                    <?php if($model->status_produksi != 3): ?>
                        <th class="text-center">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listProses as $index=>$val): ?>
                    <tr>
                        <td class="text-center"><?=$index+1 ?></td>
                        <td><?=$val['proses_name'] ?></td>
                        <td class="text-center"><?=$val['attributes']['proses_id'] ?></td>
                        <td class="text-center"><?=$val['attributes']['uk_potong'] ?></td>
                        <td class="text-right">
                            <?=number_format($val['attributes']['qty_proses']).' LB' ?>
                            <br />
                            <?=$val['sisa'] ?>
                        </td>
                        <td class="text-right"><?=number_format($val['attributes']['qty_hasil']).' LB' ?></td>
                        <td class="text-right"><?=number_format($val['attributes']['qty_rusak']).' LB' ?></td>
                        <td class="text-center"><?=$val['status_produksi'] ?></td>
                        <?php if($model->status_produksi != 3): ?>
                            <td class="text-center">
                                <button class="btn btn-default"
                                    data-button="get_data"
                                    data-spk="<?=$val['attributes']['no_spk'] ?>"
                                    data-item="<?=$val['attributes']['item_code'] ?>"
                                    data-id="<?=$val['attributes']['proses_id'] ?>"
                                    data-mesin="<?=$val['attributes']['mesin_type'] ?>">
                                    <i class="fontello icon-pencil"></i>
                                    <span>Atur Proses</span>
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
<!-- /Detail list proses -->
<!-- Detail list history -->
<?php if(count($historyNotOutsource) > 0): ?>
    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-40">
        <h4>Detail History Proses</h4>
        <hr />
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <?php foreach($historyNotOutsource as $supplierName=>$listProses): ?>
            <strong class="font-size-14"><?='Supplier: '.$supplierName ?></strong>
            <table class="table table-bordered table-custom margin-top-10 margin-bottom-0">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Tgl. SPK</th>
                        <th class="text-center">Proses</th>
                        <th class="text-center">Urutan</th>
                        <th class="text-center">Operator</th>
                        <th class="text-center">Qty Proses</th>
                        <th class="text-center">Qty Hasil</th>
                        <th class="text-center">Qty Rusak</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listProses as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=$index+1 ?></td>
                            <td class="text-center"><?=date('d-m-Y', strtotime($val['attributes']['tgl_spk'])) ?></td>
                            <td>
                                <?=$val['proses_name'] ?>
                                <br />
                                <?=(!empty($val['attributes']['uk_potong'])) ? '<span class="font-size-10 text-muted">'.$val['attributes']['uk_potong'].'</span>' : '-' ?>
                            </td>
                            <td class="text-center"><?=$val['attributes']['urutan'] ?></td>
                            <td>
                                <?=$val['operator_name'] ?>
                                <br />
                                <?='<span class="font-size-10 text-muted">'.$val['mesin_name'].'</span>' ?>
                            </td>
                            <td class="text-right">
                                <?=number_format($val['attributes']['qty_proses']).' LB' ?>
                                <br />
                                <?=$val['sisa'] ?>
                            </td>
                            <td class="text-right"><?=number_format($val['attributes']['qty_hasil']).' LB' ?></td>
                            <td class="text-right"><?=number_format($val['attributes']['qty_rusak']).' LB' ?></td>
                            <td class="text-center"><?=$val['status_produksi'] ?></td>
                            <td class="text-center">
                                <?php if($val['status_produksi'] != 1 && $model['status_produksi'] != 3): ?>
                                    <button class="btn btn-warning btn-xs btn-sm"
                                        data-button="popup_input"
                                        data-spk="<?=$val['attributes']['no_spk'] ?>"
                                        data-item="<?=$val['attributes']['item_code'] ?>"
                                        data-id="<?=$val['attributes']['proses_id'] ?>"
                                        data-urutan="<?=$val['attributes']['urutan'] ?>">
                                        <i class="fontello icon-pencil"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-primary btn-xs btn-sm"
                                    data-button="print"
                                    data-spk="<?=$val['attributes']['no_spk'] ?>"
                                    data-item="<?=$val['attributes']['item_code'] ?>"
                                    data-id="<?=$val['attributes']['proses_id'] ?>"
                                    data-urutan="<?=$val['attributes']['urutan'] ?>">
                                    <i class="fontello icon-print"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-right-0">
                <div class="text-right">
                    <?=$model->descRusak ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if(count($historyWithOutsource) > 0): ?>
    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-40">
        <h4>Detail Outsources</h4>
        <hr />
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <?php foreach($historyWithOutsource as $supplierName=>$listProses): ?>
            <strong class="font-size-14"><?='Supplier: '.$supplierName ?></strong>
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Tgl. SPK</th>
                        <th class="text-center">Proses</th>
                        <th class="text-center">Outsource</th>
                        <th class="text-center">No. SJ</th>
                        <th class="text-center">Qty Proses</th>
                        <th class="text-center">Qty Hasil</th>
                        <th class="text-center">Qty Rusak</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listProses as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=$index+1 ?></td>
                            <td class="text-center"><?=date('d-m-Y', strtotime($val['attributes']['tgl_spk'])) ?></td>
                            <td>
                                <?=$val['proses_name'] ?>
                                <br />
                                <?=(!empty($val['attributes']['uk_potong'])) ? '<span class="font-size-10 text-muted">'.$val['attributes']['uk_potong'].'</span>' : '-' ?>
                            </td>
                            <td><?=$val['outsource_name'] ?></td>
                            <td>
                                <?=$val['attributes']['no_sj'] ?>
                                <br />
                                <?='<span class="font-size-10 text-muted">Nopol: '. $val['kendaraan']['nopol'].'</span>' ?>
                            </td>
                            <td class="text-right">
                                <?=number_format($val['attributes']['qty_proses']).' LB' ?>
                                <br />
                                <?=$val['sisa'] ?>
                            </td>
                            <td class="text-right"><?=number_format($val['attributes']['qty_hasil']).' LB' ?></td>
                            <td class="text-right"><?=number_format($val['attributes']['qty_rusak']).' LB' ?></td>
                            <td class="text-center"><?=$val['status_produksi'] ?></td>
                            <td class="text-center">
                                <?php if($val['status_produksi'] != 1 && $model['status_produksi'] != 3): ?>
                                    <button class="btn btn-warning btn-xs btn-sm"
                                        data-button="popup_input"
                                        data-spk="<?=$val['attributes']['no_spk'] ?>"
                                        data-item="<?=$val['attributes']['item_code'] ?>"
                                        data-id="<?=$val['attributes']['proses_id'] ?>"
                                        data-urutan="<?=$val['attributes']['urutan'] ?>">
                                        <i class="fontello icon-pencil"></i>
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-primary btn-xs btn-sm"
                                    data-button="print"
                                    data-spk="<?=$val['attributes']['no_spk'] ?>"
                                    data-item="<?=$val['attributes']['item_code'] ?>"
                                    data-id="<?=$val['attributes']['proses_id'] ?>"
                                    data-urutan="<?=$val['attributes']['urutan'] ?>">
                                    <i class="fontello icon-print"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<!-- /Detail list history -->