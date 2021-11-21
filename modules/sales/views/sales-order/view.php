<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */

$this->title = 'No. Sales Order: '.$model->no_so;
$this->params['breadcrumbs'][] = ['label' => 'Sales Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-order-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php if($model->post == 0): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_so' => $model->no_so], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_so' => $model->no_so], [
                'class' => 'btn btn-danger btn-flat btn-sm',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'no_so',
                    'tgl_so',
                    'no_po',
                    'tgl_po',
                    [
                        'attribute' => 'customer_code',
                        'value' => function($model, $key)
                        {
                            return (isset($model->customer)) ? $model->customer->name : '';
                        }
                    ],
                    'ekspedisi_name',
                    [
                        'attribute' => 'biaya_pengiriman',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return (!empty($model->biaya_pengiriman)) ? '<strong>Rp.'.number_format($model->biaya_pengiriman).'</strong>' : null;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'value'=> function ($model, $index) { 
                            return ($model->status == 1) ? 'Active' : 'Delete';
                        }
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'up_produksi',
                        'format' => 'raw',
                        'value'=> function ($model, $index) { 
                            return (!empty($model->up_produksi)) ? '<strong>'.$model->up_produksi.' %</strong>' : '';
                        }
                    ],
                    [
                        'attribute' => 'ppn',
                        'format' => 'raw',
                        'value'=> function ($model, $index) { 
                            return (!empty($model->ppn)) ? '<strong>'.$model->ppn.' %</strong>' : '';
                        }
                    ],
                    [
                        'attribute' => 'total_order',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return '<strong>Rp.'.number_format($model->total_order).'</strong>';
                        }
                    ],
                    [
                        'attribute' => 'total_biaya',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return '<strong>Rp.'.number_format($model->total_biaya).'</strong>';
                        }
                    ],
                    [
                        'attribute' => 'grand_total',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return '<strong>Rp.'.number_format($model->grand_total).'</strong>';
                        }
                    ],
                    [
                        'attribute' => 'post',
                        'format' => 'raw',
                        'value' => function ($model, $index) { 
                            return $model->statusPost;
                        }
                    ],
                    [
                        'attribute'=>'created_at',
                        'value'=> function ($model, $index) { 
                            if(!empty($model->created_at))
                            {
                                return date('d-m-Y H:i:s',$model->created_at);
                            }
                        }
                    ],
                    [
                        'attribute'=>'updated_at',
                        'value'=> function ($model, $index) { 
                            if(!empty($model->updated_at))
                            {
                                return date('d-m-Y H:i:s',$model->updated_at);
                            }
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="margin-top-20"></div>
        <?php if(count($model->details) > 0): 
            $totalOrder = 0;
            $totalBiaya = 0;
            $totalMaterial = 0; ?>
            <div class="document-container">
                <div class="document-header">Job / Type: <?=(isset($model->order)) ? $model->order->name .' ('.(($model->type_order==1) ? 'Produk' : 'Jasa / Outsourcing').')' : '' ?></div>
                <div class="document-body">
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <h6>Detail Material</h6>
                        <hr />
                    </div>
                    <?php foreach($model->details as $index=>$val): 
                        $totalOrder += $val->total_order;
                        $totalMaterial = $val->inventoryStock->satuanTerkecil($val->item_code, [
                            0=>$val->qty_order_1, 
                            1=>$val->qty_order_2]); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0" render="detail">
                            <!-- Detail Material -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">Material</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <span class="font-size-12"><?=$val->item_code.' - '.$val->item->name ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">QTY Order</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12">
                                            <?php for($a=1;$a<3;$a++): ?>
                                                <?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).' '.$val['um_'.$a] : null ?>
                                            <?php endfor; ?>
                                        </strong>
                                        <span class="text-muted font-size-12">
                                            <?='('.$totalMaterial.' LEMBAR)' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0 margin-bottom-20">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">Harga Jual (Rp)</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12">
                                            <?php for($a=1;$a<3;$a++): ?>
                                                <?=(!empty($val['harga_jual_'.$a])) ? 
                                                    '<span class="text-money">Rp.'.number_format($val['harga_jual_'.$a]).'.-</span>
                                                    <span class="text-muted font-size-10">(Per Lembar '.$val['um_'.$a].')</span><br />' 
                                                    : null ?>
                                            <?php endfor; ?>
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">P x L</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12"><?=$val->panjang.' x '.$val->lebar ?></strong>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">Total Potong</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12"><?=$val->total_potong.' <span class="text-muted font-size-10">(Jumlah cetak '.number_format($val['jumlah_cetak']).')</span>' ?></strong>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">Total Objek</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12"><?=$val->total_objek.' <span class="text-muted font-size-10">(Jumlah objek '.number_format($val['jumlah_objek']).')</span>' ?></strong>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">Total Warna</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12"><?=$val->total_warna ?></strong>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <label class="font-size-12">Lembar Ikat</label>
                                    </div>
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                                        <strong class="font-size-12">
                                            <?=(!empty($val->lembar_ikat_1) ? number_format($val->lembar_ikat_1) .' '.$val->lembar_ikat_um_1 .' / ' : '') ?>
                                            <?=(!empty($val->lembar_ikat_2) ? number_format($val->lembar_ikat_2) .' '.$val->lembar_ikat_um_2 .' / ' : '') ?>
                                            <?=(!empty($val->lembar_ikat_3) ? number_format($val->lembar_ikat_3) .' '.$val->lembar_ikat_um_3 : '') ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <!-- /Detail Material -->
                            <!-- Detail Proses -->
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                                <div class="col-lg-12 col-md-12 col-xs-12 text-left padding-left-0">
                                    <span class="font-size-16">Detail Proses</span>
                                    <hr />
                                    <?php if(count($val->detailsProduksi) > 0):
                                        $total_biaya=0;?>
                                        <ul>
                                            <?php foreach($val->detailsProduksi as $v):
                                                $total_biaya += $v->total_biaya;
                                                $totalBiaya += $v->total_biaya; ?>
                                                <li>
                                                    <span class="label"><?=$v->name ?></span>
                                                    <span class="currency"><?='Rp. '.number_format($v->total_biaya).'.-' ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                            <li>
                                                <span class="label text-right">Total Biaya</span>
                                                <span class="currency summary"><?='Rp. '.number_format($total_biaya).'.-' ?></span>
                                            </li>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- /Detail Proses -->
                        </div>
                    <?php endforeach; ?>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 text-right" render="summary">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="col-lg-10 col-md-10 col-xs-12">
                                <span class="label-summary">Total Material</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12">
                                <span class="value-summary"><?='Rp. '.number_format($totalOrder).'.-' ?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="col-lg-10 col-md-10 col-xs-12">
                                <span class="label-summary">Total Biaya</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12">
                                <span class="value-summary"><?=' Rp. '.number_format($totalBiaya).'.-' ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="col-lg-10 col-md-10 col-xs-12">
                                <span class="label-summary">Biaya Pengiriman</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12">
                                <span class="value-summary"><?=' Rp. '.number_format($model->biaya_pengiriman).'.-' ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="col-lg-10 col-md-10 col-xs-12">
                                <span class="label-summary">PPN (%)</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12">
                                <span class="value-summary"><?=(!empty($model->ppn)) ? $model->ppn.' %' : '-' ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="col-lg-10 col-md-10 col-xs-12">
                                <span class="label-summary">Grand Total</span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12">
                                <span class="value-summary"><?=' Rp. '.number_format($model->grand_total).'.-' ?>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($model->up_produksi) || $model->up_produksi !=0): ?>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <p class="font-bold margin-bottom-0 margin-top-10 text-right">Keterangan: </p>
                            <p class="font-bold margin-bottom-0 text-danger text-right">
                                <i>
                                    Up Produksi sebesar <?=$model->up_produksi .'%.' ?>
                                    Tambahan QTY Material sebanyak <?=$totalMaterial*($model->up_produksi/100) ?> Lembar Plano.
                                </i>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if($model->post==0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'no_so'=>$model->no_so], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
</div>