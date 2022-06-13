<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'Nama Job: '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-order-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
    </p>
    <div class="form-container no-background" render="detail">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Job</h6>
            <?php if($model->status == 0): ?>
                <span class="text-label text-danger">Cancel Order</span>
            <?php endif; ?>
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
                    <span><?=$model->code ?></span>
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
                    <span><?=date('d-m-Y', strtotime($model->tgl_po)) ?></span>
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
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
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
                    <label>Ekspedisi</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->ekspedisi)) ? $model->ekspedisi->name : '-' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Sales</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(isset($model->sales)) ? $model->sales->name : '' ?></span>
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
                    <label>PPN</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=(!empty($model->ppn)) ? $model->ppn.'%' : '-' ?></span>
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
        <!-- detail item -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Item</h6>
            <hr class="margin-top-0" />
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Potong</th>
                        <th class="text-center">PxL</th>
                        <th class="text-center">Objek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsMaterial) > 0): ?>
                        <?php foreach($model->itemsMaterial as $no=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-center"><?=$val->item_code ?></td>
                                <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                                <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                                <td class="text-right">
                                    <?=$val->qty_order_1 .' '. $val->um_1 ?>
                                    <br />
                                    <?=(!empty($val->qty_up)) ? '<i class="font-size-10 text-muted">Up '.$val->qty_up .' Lembar</i>': '' ?>
                                </td>
                                <td class="text-center"><?=$val->total_potong ?></td>
                                <td class="text-center">
                                    <?php foreach($val->potongs as $pt): ?>
                                        <div class="border-custom">
                                            <?='<span>'.$pt->panjang.'x'.$pt->lebar .'</span>' ?>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                                <td class="text-center">
                                    <?php foreach($val->potongs as $pt): ?>
                                        <div class="border-custom">
                                            <?='<span>'.$pt->objek .'</span>' ?>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class="text-center text-danger" colspan="15"><i>Data is empty ...</i></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail item -->
        <!-- detail proses -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Proses</h6>
            <hr class="margin-top-0" />
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Proses Produksi</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->proses) > 0): ?>
                        <?php foreach($model->proses as $no=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-muted text-left">
                                    <?='<i>'.$val->prosesProduksi->name.'</i>' ?>
                                </td>
                                <td class="text-muted text-left">
                                    <?='<i>'.$val->keterangan.'</i>' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="5">Data masih kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail proses -->
        <!-- detail bahan pembantu -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Bahan Pembantu</h6>
            <hr class="margin-top-0" />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsNonMaterial) > 0): ?>
                        <?php foreach($model->itemsNonMaterial as $no=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-center"><?=$val->item_code ?></td>
                                <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                                <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                                <td class="text-center"><?=$val->qty_order_1 .' '.$val->um_1 ?></td>
                                <td class="text-center"><?=$val->item->material->name ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="8">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail bahan pembantu -->
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
        <?php if($model->status == 1 && $cancelOrder): ?>
            <?= Html::a('<i class="fontello icon-cancel"></i><span>Cancel Order</span>', ['delete', 'code' => $model->code], [
                'class' => 'btn btn-danger btn-flat btn-sm',
                'data' => ['confirm' => 'Are you sure you want to cancel order?', 'method' => 'post'],
            ]) ?>
        <?php endif; ?>
        <?php if(\Yii::$app->user->identity->profile->typeUser->value == 'ADMINISTRATOR' || \Yii::$app->user->identity->profile->typeUser->value == 'OWNER'): ?>
            <?= Html::a('<i class="fontello icon-list"></i><span>Invoice Sales Order</span>', ['invoice', 'code'=>$model->code], ['class' => 'btn btn-warning btn-flat btn-sm', 'target'=>'_blank']) ?>
        <?php endif; ?>
        <?php if($model->post==0 && $model->status == 1): ?>
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'code'=>$model->code], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        <?php endif; ?>
    </div>
</div>