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
                    'total_ppn',
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
                            <strong><?=(isset($model->profile)) ? $model->profile->name : '-' ?></strong>
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
                                <th class="text-center">Satuan</th>
                                <th class="text-center">QTY Order</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Ppn (%)</th>
                                <th class="text-center">Total Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($model->details) > 0): 
                                $totalOrder=0; ?>
                                <?php foreach($model->details as $index=>$val): 
                                    $totalOrder += $val->total_order; ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                        <td class="text-center"><?=$val->satuan ?></td>
                                        <td class="text-right"><?=number_format($val->qty_order) ?></td>
                                        <td class="text-right"><?=number_format($val->harga_beli).'.-' ?></td>
                                        <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
                                        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right" colspan="6"><strong>Total Order:</strong></td>
                                    <td class="text-right"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td class="text-center text-danger" colspan="10">Data is empty</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="beauty-line"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">QTY Terima</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Ppn (%)</th>
                                <th class="text-center">Total Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($model->details) > 0): 
                                $totalInvoice=0; ?>
                                <?php foreach($model->details as $index=>$val): 
                                    $totalInvoice += $val->total_invoice; ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                        <td class="text-center"><?=$val->satuan ?></td>
                                        <td class="text-right"><?=number_format($val->qty_terima) ?></td>
                                        <td class="text-right"><?=number_format($val->harga_beli).'.-' ?></td>
                                        <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
                                        <td class="text-right"><?=number_format($val->total_invoice).'.-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right" colspan="6"><strong>Total Invoice:</strong></td>
                                    <td class="text-right"><strong><?=number_format($totalInvoice).'.-' ?></strong></td>
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
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to Terima Invoice</span>', ['terima', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if($model->status_terima==1 && $model->post==0): ?>
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to Stock Material</span>', ['post', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        <?php endif; ?>
    </p>
</div>