<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrderInvoice */

$this->title = 'No. Invoice: '.$model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-order-invoice-view">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-4 col-md-6 col-xs-12 padding-left-0 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'no_invoice',
                    'tgl_invoice',
                    'no_bukti',
                ],
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12 padding-left-0 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'total_ppn',
                        'value' => function($model, $index){
                            return number_format($model->total_ppn).'%';
                        }
                    ],
                    [
                        'attribute' => 'total_invoice',
                        'value' => function($model, $index){
                            return number_format($model->total_invoice);
                        }
                    ],
                    'keterangan:ntext',
                ],
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12 padding-left-0 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'status_terima',
                        'format' => 'raw',
                        'value' => function ($model, $index) { 
                            return $model->statusTerima;
                        }
                    ],
                    [
                        'attribute' => 'post',
                        'format' => 'raw',
                        'value' => function ($model, $index) { 
                            return $model->statusPost;
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="margin-top-20"></div>
        <div class="document-container">
            <div class="document-header">No. Purchase Order: <?=$model->no_po ?></div>
            <div class="document-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="row">
                            <label class="div-label">No. PO</label>
                            <strong><?=$model->no_po ?></strong>
                        </div>
                        <div class="row">
                            <label class="div-label">Tgl. PO</label>
                            <strong><?=$model->tgl_po ?></strong>
                        </div>
                        <div class="row">
                            <label class="div-label">Tgl. Kirim</label>
                            <strong><?=$model->tgl_kirim ?></strong>
                        </div>
                        <div class="row">
                            <label class="div-label">Term IN</label>
                            <strong><?=$model->term_in .' Hari' ?></strong>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding-left-0">
                        <div class="row">
                            <label class="div-label">User Request</label>
                            <strong><?=(isset($model->purchase->request)) ? $model->purchase->request->name : '-' ?></strong>
                        </div>
                        <div class="row">
                            <label class="div-label">Supplier</label>
                            <strong><?=(isset($model->supplier)) ? $model->supplier->name : '' ?></strong>
                        </div>
                        <div class="row">
                            <label class="div-label">Total Order</label>
                            <strong><?='Rp.'.number_format($model->total_order).'.-' ?></strong>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0">
                    <div class="margin-top-20"></div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item</th>
                                <th class="text-center" colspan="2">QTY Order</th>
                                <th class="text-center" colspan="2">QTY Terima</th>
                                <th class="text-center" colspan="2">Harga Beli (Rp)</th>
                                <th class="text-center">Ppn (%)</th>
                                <th class="text-center">Total Order (Rp)</th>
                                <th class="text-center">Total Invoice (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($model->details) > 0): 
                                $totalOrder=0;
                                $totalInvoice=0; ?>
                                <?php foreach($model->details as $index=>$val): 
                                    $totalOrder += $val->total_order;
                                    $totalInvoice += $val->total_invoice; ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                        <?php for($a=1;$a<3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <?php for($a=1;$a<3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_terima_'.$a])) ? number_format($val['qty_terima_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <?php for($a=1;$a<3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
                                        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                        <td class="text-right"><?=number_format($val->total_invoice).'.-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right summary" colspan="9"></td>
                                    <td class="text-right summary"><strong><?='Total Order: '.number_format($totalOrder).'.-' ?></strong></td>
                                    <td class="text-right summary"><strong><?='Total Invoice: '.number_format($totalInvoice).'.-' ?></strong></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td class="text-center text-danger" colspan="10">Data is empty</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <p class="text-right">
        <?php if($model->status_terima==0): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-ok"></i><span>Konfirmasi Terima Item Material</span>', ['terima', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        <?php endif; ?>
    </p>
</div>