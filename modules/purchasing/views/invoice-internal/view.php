<?php
use app\models\User;
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
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'no_invoice',
                [
                    'attribute' => 'tgl_invoice',
                    'value' => function($model, $value) {
                        return (!empty($model->tgl_invoice)) ? date('d-m-Y', strtotime($model->tgl_invoice)) : null;
                    }
                ],
                'no_bukti',
                [
                    'attribute' => 'total_invoice',
                    'value' => function($model, $index){
                        return number_format($model->total_invoice);
                    }
                ],
                'keterangan:ntext',
                [
                    'attribute' => 'post',
                    'format' => 'raw',
                    'value' => function ($model, $index) { 
                        return $model->statusPost;
                    }
                ],
                [
                    'attribute' => 'status_terima',
                    'format' => 'raw',
                    'value' => function ($model, $index) { 
                        return $model->statusTerima;
                    }
                ],
            ],
        ]) ?>
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
                            <strong><?=date('d-m-Y', strtotime($model->tgl_po)) ?></strong>
                        </div>
                        <div class="row">
                            <label class="div-label">Tgl. Kirim</label>
                            <strong><?=date('d-m-Y', strtotime($model->tgl_kirim)) ?></strong>
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
                                <th class="text-center">QTY Order</th>
                                <th class="text-center">QTY Terima</th>
                                <th class="text-center">Harga Beli (Rp)</th>
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
                                        <td class="font-size-10"><?=(isset($val->barang)) ? '<span class="text-success">'.$val->barang->code .'</span><br />'. $val->barang->name : '' ?></td>
                                        <td class="text-right"><?=number_format($val->qty_order).'<br /><span class="text-muted font-size-10">'.$val->um.'</span>' ?></td>
                                        <td class="text-right">
                                            <?php if($val->qty_selisih > 0): ?>
                                                <?=number_format($val->qty_terima).' <span class="text-muted font-size-12">'.$val->um.'</span>' ?>
                                                <br />
                                                <strong class="text-danger">
                                                    <?='<i>Kurang '.number_format($val->qty_selisih).' '.$val->um.'</i>' ?>
                                                </strong>
                                            <?php else: ?>
                                                <?=number_format($val->qty_terima).'<br /><span class="text-muted font-size-10">'.$val->um.'</span>' ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right"><?=number_format($val->harga_beli).'.- <br /><span class="text-muted font-size-10">Per '.$val->um.'</span>'?></td>
                                        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                        <td class="text-right"><?=number_format($val->total_invoice).'.-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right summary" colspan="5"></td>
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
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('invoice-asset-dan-not-asset[U]')): ?>
            <?php if($model->status_terima != 1 && $model->status_terima != 3): ?>
                <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
                <?php if($model->status_terima==0): ?>
                    <?= Html::a('<i class="fontello icon-ok"></i><span>Konfirmasi Terima Material</span>', ['terima', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                <?php else: ?>
                    <?= Html::a('<i class="fontello icon-cancel"></i><span>Close Penerimaan</span>', ['close', 'no_invoice' => $model->no_invoice], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </p>
</div>