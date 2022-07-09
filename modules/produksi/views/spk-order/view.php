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
    .in-block { display: inline-block; }
</style>
<div class="spk-view">
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('proses-produksi-sales-order[U]')): ?>
        <p class="text-right">
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Proses Produksi</span>', ['layar', 'no_spk' => $model->no_spk], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        </p>
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
    </div>
</div>