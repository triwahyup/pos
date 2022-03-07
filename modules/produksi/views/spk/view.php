<?php
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
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Proses Produksi</span>', ['update', 'no_spk' => $model->no_spk], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
    </p>

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
                    <label>Type Order</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->typeOrder ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>No. SPK</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->no_spk ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. SPK</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->tgl_spk)) ?></span>
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
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
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
                    <label>Term In</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->term_in.' Hari' ?></span>
                    <div>
                        <i class="text-muted font-size-10">
                            <?='Tgl. Jatuh Tempo Pembayaran: '.date('d-m-Y', strtotime('+'.$model->term_in.' days', strtotime($model->tgl_so)))?>
                        </i>
                    </div>
                </div>
            </div>
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
                    <label>Up Produksi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->up_produksi)) ? $model->up_produksi.'%' : '-' ?></span>
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
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Tgl. SPK</th>
                        <th class="text-center">Proses</th>
                        <th class="text-center">Uk. Potong</th>
                        <th class="text-center">Mesin</th>
                        <th class="text-center">Operator</th>
                        <th class="text-center">Qty Proses (LB)</th>
                        <th class="text-center">Qty Hasil (LB)</th>
                        <th class="text-center">Qty Rusak (LB)</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->produksiInAlls) > 0): ?>
                        <?php foreach($model->produksiInAlls as $index=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$index+1 ?></td>
                                <td class="text-center"><?=date('d-m-Y', strtotime($val->tgl_spk)) ?></td>
                                <td><?=(isset($val->proses)) ? $val->proses->name : '' ?></td>
                                <td class="text-center"><?=$val->uk_potong ?></td>
                                <td><?=(isset($val->mesin)) ? $val->mesin->name : '' ?></td>
                                <td><?=(isset($val->operator)) ? $val->operator->name : '' ?></td>
                                <td class="text-right"><?=number_format($val->qty_proses) ?></td>
                                <td class="text-right"><?=number_format($val->qty_hasil) ?></td>
                                <td class="text-right"><?=number_format($val->qty_rusak) ?></td>
                                <td class="text-center"><?=$val->statusProduksi ?></td>
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
        <!-- Detail list proses -->
    </div>
</div>