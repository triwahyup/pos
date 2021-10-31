<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterOrder */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-order-view">
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

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 pading-right-0">
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    [
                        'attribute' => 'type_order',
                        'value' => function($model, $key)
                        {
                            return ($model->type_order == 1) ? 'Produk' : 'Jasa';
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'value'=> function ($model, $index) { 
                            return ($model->status == 1) ? 'Active' : 'Delete';
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
        <div class="col-lg-6 col-md-6 col-xs-12 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'total_order',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return '<strong>Rp. '.number_format($model->total_order).'</strong>';
                        }
                    ],
                    [
                        'attribute' => 'total_biaya',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return '<strong>Rp. '.number_format($model->total_biaya).'</strong>';
                        }
                    ],
                    [
                        'attribute' => 'grand_total',
                        'format' => 'raw',
                        'value' => function($model, $key)
                        {
                            return '<strong>Rp. '.number_format($model->grand_total).'</strong>';
                        }
                    ],
                    'keterangan',
                ]
            ]) ?>
        </div>
    </div>
    <?php if(count($model->details) > 0): 
        $totalOrder = 0;
        $totalBiaya = 0; ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 margin-top-40">
            <h6>Detail Material</h6>
            <hr />
        </div>
        <?php foreach($model->details as $index=>$val): 
            $totalOrder += $val->total_order; ?>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0" data-form="detail">
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
                                <?='('.$val->inventoryStock->satuanTerkecil($val->item_code, [0=>$val->qty_order_1, 1=>$val->qty_order_2]).' LEMBAR)' ?>
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
                                        'Rp.'.number_format($val['harga_jual_'.$a]).'.-
                                        <span class="text-muted font-size-10">(Per '.$val['um_'.$a].')</span><br />' 
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
                            <label class="font-size-12">Total Warna / Lb.Ikat</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12"><?=$val->total_warna.' / '.$val->typeIkat ?></strong>
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
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0" data-form="summary">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <span class="text-right"><?='Total Material: Rp. '.number_format($totalOrder).'.-' ?></span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <span class="text-right"><?='Total Biaya: Rp. '.number_format($totalBiaya).'.-' ?></span>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                <span class="text-right"><?='Grand Total: Rp. '.number_format($totalOrder+$totalBiaya).'.-' ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>