<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'No. Surat Perintah Kerja: '.$model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Surat Perintah Kerja', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<style>
    .in-block {
        display: inline-block;
    }
</style>
<div class="spk-view">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('proses-produksi-sales-order[U]')): ?>
        <?php if($model->status_produksi == 1 || $model->status_produksi == 2): ?>
            <p class="text-right">
                <?= Html::a('<i class="fontello icon-pencil"></i><span>Proses Produksi</span>', [
                    'tab', 'no_spk' => $model->no_spk], [
                    'class' => 'btn btn-warning btn-flat btn-sm']) ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="form-container no-background" render="detail">
        <!-- Detail Job --->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6 class="in-block">Detail Job</h6>
            <?=$model->statusProduksi ?>
            <hr />
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Nama Job</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->name ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Nick Job</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->nick_name ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>No. SO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->no_so ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. SO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->tgl_so)) ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Kode Repeat</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->repeat_code )) ? $model->repeat_code : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Customer</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(isset($model->customer)) ? $model->customer->name : '' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Deadline</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->deadline)) ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Type Order</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->typeOrder ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Ekspedisi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->ekspedisi)) ? $model->ekspedisi->name : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Total Qty</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->total_qty .(($model->type_qty == 1) ? ' RIM' : ' LEMBAR')  ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Total Qty Up</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=($model->type_qty == 1) ? $model->total_qty_up .' LEMBAR' : ''  ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Up Produksi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->up_produksi)) ? $model->up_produksi.'%' : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Lb. Ikat</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span>
                        <?=(!empty($model->lembar_ikat_1) ? number_format($model->lembar_ikat_1) .' '.$model->lembar_ikat_um_1 .' / ' : '') ?>
                        <?=(!empty($model->lembar_ikat_2) ? number_format($model->lembar_ikat_2) .' '.$model->lembar_ikat_um_2 .' / ' : '') ?>
                        <?=(!empty($model->lembar_ikat_3) ? number_format($model->lembar_ikat_3) .' '.$model->lembar_ikat_um_3 : '') ?>
                    </span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Total Warna</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->total_warna ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Keterangan</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->keterangan ?></span>
                </div>
            </div>
        </div>
        <!-- /Detail Job --->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30"></div>
        <!-- Detail list proses -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Proses</h6>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <?php foreach($model->produksiInAlls as $supplierName=>$listProses): ?>
                <strong class="font-size-14"><?='Supplier: '.$supplierName ?></strong>
                <table class="table table-bordered table-custom margin-top-10">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Proses</th>
                            <th class="text-center">Urutan Proses</th>
                            <th class="text-center">Uk. Potong</th>
                            <th class="text-center">Qty Proses</th>
                            <th class="text-center">Qty Hasil</th>
                            <th class="text-center">Qty Rusak</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($listProses as $index=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$index +1 ?></td>
                                <td><?=$val['proses_name'] ?></td>
                                <td class="text-center"><?=$val['attributes']['proses_id'] ?></td>
                                <td class="text-center"><?=$val['attributes']['uk_potong'] ?></td>
                                <td class="text-right"><?=number_format($val['attributes']['qty_proses']).' LB' ?></td>
                                <td class="text-right"><?=number_format($val['attributes']['qty_hasil']).' LB' ?></td>
                                <td class="text-right"><?=number_format($val['attributes']['qty_rusak']).' LB' ?></td>
                                <td class="text-center"><?=$val['status_produksi'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
        <!-- Detail list proses -->
        <!-- Detail list history -->
        <?php if(count($historyNotOutsource) > 0): ?>
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-40">
                <h6>Detail History Proses</h6>
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
                            <?=$val['desc_rusak'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if(count($historyWithOutsource) > 0): ?>
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-40">
                <h6>Detail Outsources</h6>
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

        <?php if($model->status_produksi == 3): ?>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <?= Html::a('<i class="fontello icon-info-4 font-size-18"></i>
                    <span class="margin-left-10">Kembali ke proses produksi?</span>', 
                    ['post', 'no_spk'=>$model->no_spk, 'type' => \Yii::$app->params['ON_FINISH']], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
                <?= Html::a('<i class="fontello icon-ok"></i>
                    <span class="margin-left-10">Closing</span>', 
                    ['post', 'no_spk'=>$model->no_spk, 'type' => \Yii::$app->params['ON_CLOSING']], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>