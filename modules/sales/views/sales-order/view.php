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

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 pading-right-0">
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
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="margin-top-20"></div>
        <fieldset class="fieldset-box">
            <legend>Data Detail</legend>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 padding-left-0">
                    <span>Nama Order (Job)</span>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12 padding-left-0">
                    <span>: <?=(isset($model->order)) ? $model->order->name : '' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 padding-left-0">
                    <span>Type Order</span>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-12 padding-left-0">
                    <span>: <?=($model->type_order==1) ? 'Produk' : 'Jasa / Outsourcing' ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="margin-top-20"></div>
                <table class="table table-bordered table-custom" data-table="detail">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Item</th>
                            <th class="text-center" colspan="2">QTY</th>
                            <th class="text-center" colspan="2">Harga Material (Rp)</th>
                            <th class="text-center" colspan="2">QTY Cetak</th>
                            <th class="text-center">Harga Cetak (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($model->details) > 0):
                            $totalOrder = 0;
                            $totalBiaya = 0; ?>
                            <?php foreach($model->details as $index=>$val):
                                $totalOrder += $val->total_order; ?>
                                <tr>
                                    <td class="text-center" rowspan="2"><?=$index+1?></td>
                                    <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                    <?php for($a=1;$a<3;$a++): ?>
                                        <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                    <?php endfor; ?>
                                    <?php for($a=1;$a<3;$a++): ?>
                                        <td class="text-right"><?=(!empty($val['harga_jual_'.$a])) ? number_format($val['harga_jual_'.$a]).'.-<br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
                                    <?php endfor; ?>
                                    <td class="text-right"><?=number_format($val['jumlah_cetak']).'.- <br /><span class="text-muted font-size-10">QTY Cetak</span>' ?></td>
                                    <td class="text-right"><?=number_format($val['jumlah_objek']).'.- <br /><span class="text-muted font-size-10">QTY Objek</span>' ?></td>
                                    <td class="text-right"><?=number_format($val['harga_cetak']).'.- <br /><span class="text-muted font-size-10">Per Objek</span>' ?></td>
                                </tr>
                                <tr>
                                    <td colspan="10">
                                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                                                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0 padding-right-0">
                                                        <div class="td-desc">
                                                            <label>Panjang</label>
                                                            <span><?=$val->panjang ?></span>
                                                        </div>
                                                        <div class="td-desc">
                                                            <label>Lebar</label>
                                                            <span><?=$val->lebar ?></span>
                                                        </div>
                                                        <div class="td-desc">
                                                            <label>Potong</label>
                                                            <span><?=$val->potong ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0 padding-right-0">
                                                        <div class="td-desc">
                                                            <label>Objek</label>
                                                            <span><?=$val->objek ?></span>
                                                        </div>
                                                        <div class="td-desc">
                                                            <label>Mesin</label>
                                                            <span><?=$val->mesin ?></span>
                                                        </div>
                                                        <div class="td-desc">
                                                            <label>Warna</label>
                                                            <span><?=$val->jumlah_warna ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 padding-left-0 padding-right-0">
                                                        <div class="td-desc">
                                                            <label>Lb. Ikat</label>
                                                            <span><?=$val->lembar_ikat ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                                                <div class="col-lg-12 col-md-12 col-xs-12 text-left padding-left-0">
                                                    <?php if(count($val->detailsProduksi) > 0):
                                                        $total_biaya=0;?>
                                                        <label class="text-left"><strong>Detail Proses:</strong></label>
                                                        <ul class="desc-custom padding-left-0">
                                                            <?php foreach($val->detailsProduksi as $v):
                                                                $total_biaya += $v->total_biaya;
                                                                $totalBiaya += $v->total_biaya; ?>
                                                                <li>
                                                                    <span><?=$v->name ?></span>
                                                                    <span><?='Rp. '.number_format($v->total_biaya).'.-' ?></span>
                                                                </li>
                                                            <?php endforeach; ?>
                                                            <li>
                                                                <span class="text-right"><strong>Total Biaya:</strong></span>
                                                                <span><?='Rp. '.number_format($total_biaya).'.-' ?></span>
                                                            </li>
                                                        </ul>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php
                                if(!empty($model->ppn) || $model->ppn !=0){
                                    $ppn = ($totalOrder+$totalBiaya) / ($model->ppn*100);
                                    $grandTotal = number_format($totalOrder+$totalBiaya+$ppn).'.- (PPN '.$model->ppn.'%)';
                                }else{
                                    $grandTotal = number_format($totalOrder+$totalBiaya).'.-';
                                }
                            ?>
                            <tr>
                                <td class="summary" colspan="4"></td>
                                <td class="summary" colspan="2"><strong><?='Total Order: Rp. '.number_format($totalOrder).'.-' ?></strong></td>
                                <td class="summary" colspan="2"><strong><?='Total Biaya: Rp. '.number_format($totalBiaya).'.-' ?></strong></td>
                                <td class="summary"><strong><?='Grand Total: Rp. '.$grandTotal ?></strong></td>
                            </tr>
                        <?php else : ?>
                            <tr>
                                <td class="text-center text-danger" colspan="10">Data is empty</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
    <?php if($model->post==0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 text-right">
            <div class="margin-top-20"></div>
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'no_so'=>$model->no_so], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
</div>