<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'Nama Job: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sales Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-order-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'code' => $model->code], [
            'class' => 'btn btn-danger btn-flat btn-sm',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="form-container no-background" render="detail">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Job</h6>
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
                    <label>No. SO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->code ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. SO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->tgl_so ?></span>
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
                    <label>No. PO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->no_po ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. PO</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->tgl_po ?></span>
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
                    <label>Ekspedisi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->ekspedisi_name ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Biaya Pengiriman</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->biaya_pengiriman ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Up Produksi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->up_produksi ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>PPN</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->ppn ?></span>
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
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Item</h6>
            <hr />
        </div>
        <?php foreach($model->itemsMaterial as $item): ?>
            <div class="col-lg-8 col-md-8 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">Material</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=(isset($item->item->name)) ? $item->item->code.' - '.$item->item->name : '' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label class="font-size-12">QTY Order</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-12">
                            <?php for($a=1;$a<3;$a++): ?>
                                <?=(!empty($item['qty_order_'.$a])) ? number_format($item['qty_order_'.$a]).' '.$item['um_'.$a] : null ?>
                            <?php endfor; ?>
                        </strong>
                        <span class="text-muted font-size-12">
                            <?='('.number_format($item->inventoryStock->satuanTerkecil($item->item_code, [0=>$item->qty_order_1, 1=>$item->qty_order_2])).' LEMBAR)' ?>
                        </span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <table class="table table-bordered table-custom margin-top-10">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">PxL</th>
                                <th class="text-center">Potong</th>
                                <th class="text-center">Objek</th>
                                <th class="text-center">Warna</th>
                                <th class="text-center">Lb. Ikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($item->details as $no=>$val): ?>
                                <tr>
                                    <td class="text-center"><?=$no+1 ?></td>
                                    <td class="text-center"><?=$val->panjang.'x'.$val->lebar ?></td>
                                    <td class="text-right">
                                        <?=$val->total_potong .'
                                            <span class="text-muted font-size-10">('.number_format($val->jumlah_cetak).' cetak)</span>' ?>
                                    </td>
                                    <td class="text-right">
                                        <?=$val->total_objek .'
                                            <span class="text-muted font-size-10">('.number_format($val->jumlah_objek).' objek)</span>' ?>
                                    </td>
                                    <td class="text-center"><?=$val->total_warna ?></td>
                                    <td class="text-right">
                                        <span class="font-size-10">
                                            <?=(!empty($val->lembar_ikat_1) ? number_format($val->lembar_ikat_1) .' '.$val->lembar_ikat_um_1 .' / ' : '') ?>
                                            <?=(!empty($val->lembar_ikat_2) ? number_format($val->lembar_ikat_2) .' '.$val->lembar_ikat_um_2 .' / ' : '') ?>
                                            <?=(!empty($val->lembar_ikat_3) ? number_format($val->lembar_ikat_3) .' '.$val->lembar_ikat_um_3 : '') ?>
                                        </strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Bahan Pembantu</h6>
            <hr />
        </div>
        <div class="col-lg-8 col-md-8 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">No.</th>
                        <th class="text-center" colspan="2">Item</th>
                        <th class="text-center" colspan="3">QTY</th>
                        <th class="text-center" rowspan="2">Jenis</th>
                    </tr>
                    <tr>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">1</th>
                        <th class="text-center">2</th>
                        <th class="text-center">3</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsNonMaterial) > 0): ?>
                        <?php foreach($model->itemsNonMaterial as $no=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-center"><?=$val->item_code ?></td>
                                <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                                <?php for($a=1;$a<=3;$a++):?>
                                    <td class="text-center"><?=($val['qty_order_'.$a] !=0) ? $val['qty_order_'.$a] .' '.$val['um_'.$a] : '' ?></td>
                                <?php endfor; ?>
                                <td class="text-center"><?=$val->item->material->name ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="5">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
        <?= Html::a('<i class="fontello icon-list"></i><span>Invoice Sales Order</span>', ['post', 'code'=>$model->code], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php if($model->post==0): ?>
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'code'=>$model->code], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        <?php endif; ?>
    </div>
</div>