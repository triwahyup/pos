<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */

$this->title = $model->no_invoice;
$this->params['breadcrumbs'][] = ['label' => 'Sales Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sales-invoice-view">
    <div class="form-container no-background" render="detail">
        <div class="col-lg-12 col-md-12 col-xs-12 margin-bottom-20">
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0">
                    <h4>
                        <strong>
                            <u>Total Invoice</u>
                        </strong>
                    </h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Order Material</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->new_total_order_material).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Order Bahan</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->new_total_order_bahan).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Biaya Produksi</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->new_total_biaya_produksi).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>Total Biaya Lain2</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->total_biaya_lain).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label>PPN (%)</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <span><?=number_format($model->new_total_ppn).'.-' ?></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right margin-top-10">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                        <label class="font-size-16">Grand Total</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <strong class="font-size-16"><?=number_format($model->new_grand_total).'.-' ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <!-- detail sales order -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Sales Order</h6>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="font-size-10 text-center" rowspan="2">Item</th>
                        <th class="font-size-10 text-center" rowspan="2">Qty</th>
                        <th class="font-size-10 text-center" colspan="2">Harga (Rp)</th>
                        <th class="font-size-10 text-center" colspan="3">Total Order (Rp)</th>
                    </tr>
                    <tr>
                        <th class="font-size-10 text-center">Per RIM</th>
                        <th class="font-size-10 text-center">Per LB</th>
                        <th class="font-size-10 text-center">Material</th>
                        <th class="font-size-10 text-center">Bahan</th>
                        <th class="font-size-10 text-center">Biaya Produksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsSo) > 0):
                        $total_order_material = 0;
                        $total_order_bahan = 0;
                        $total_biaya_produksi = 0;
                        $total_order_all = 0;
                        $total_ppn = 0;
                        $grand_total = 0;
                        foreach($model->details as $val):
                            if($val->type_invoice == 1):
                                $total_order_material = $val->new_total_order_material;
                                $total_order_all += $total_order_material;
                                $total_order_bahan = $val->new_total_order_bahan;
                                $total_order_all += $total_order_bahan;
                                $total_biaya_produksi = $val->new_total_biaya_produksi;
                                $total_order_all += $total_biaya_produksi;
                                $total_ppn = $val->new_total_ppn;
                                $grand_total = $val->new_grand_total;
                            endif;
                        endforeach; ?>
                        <?php foreach($model->itemsSo as $val):  ?>
                            <tr>
                                <?php if(!empty($val->proses_code)): ?>
                                    <td class="font-size-10 text-left"><?=(isset($val->proses)) ? $val->proses->name : '-' ?></td>
                                <?php else: ?>
                                    <td class="font-size-10 text-left">
                                        <?=(isset($val->item)) ? $val->item_code .' - '. $val->item->name : '-' ?>
                                        <br />
                                        <span class="font-size-10 text-muted"><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></span>
                                    </td>
                                <?php endif; ?>
                                <td class="font-size-10 text-right"><?=$val->qty_order_1 .' '. $val->um_1 ?></td>
                                <td class="font-size-10 text-right"><?=number_format($val->new_harga_jual_1).'.-' ?></td>
                                <td class="font-size-10 text-right"><?=number_format($val->new_harga_jual_2).'.-' ?></td>
                                <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                    <td class="font-size-10 text-right"><?=number_format($val->new_total_order).'.-' ?></td>
                                    <td></td><td></td>
                                <?php elseif($val->kode['name'] == \Yii::$app->params['TYPE_BAHAN_PB']): ?>
                                    <td></td>
                                    <td class="font-size-10 text-right"><?=number_format($val->new_total_order).'.-' ?></td>
                                    <td></td>
                                <?php else: ?>
                                    <td></td><td></td>
                                    <td class="font-size-10 text-right"><?=number_format($val->new_total_order).'.-' ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="text-right mark-2" colspan="4">TOTAL:</td>
                            <td class="text-right mark-2"><?=number_format($total_order_material).'.-' ?></td>
                            <td class="text-right mark-2"><?=number_format($total_order_bahan).'.-' ?></td>
                            <td class="text-right mark-2"><?=number_format($total_biaya_produksi).'.-' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right mark-3" colspan="6">TOTAL ORDER:</td>
                            <td class="text-right mark-3"><?=number_format($total_order_all).'.-' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right mark-3" colspan="6">TOTAL PPN:</td>
                            <td class="text-right mark-3"><?=number_format($total_ppn).'.-' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right mark-3" colspan="6"><strong>GRAND TOTAL:</strong></td>
                            <td class="text-right mark-3"><strong><?=number_format($grand_total).'.-' ?></strong></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="10">Data masih kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail sales order -->
        <!-- detail request order -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Request Order</h6>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="font-size-10 text-center" rowspan="2">Item</th>
                        <th class="font-size-10 text-center" rowspan="2">Qty</th>
                        <th class="font-size-10 text-center" colspan="2">Harga (Rp)</th>
                        <th class="font-size-10 text-center" colspan="2">Total Order (Rp)</th>
                    </tr>
                    <tr>
                        <th class="font-size-10 text-center">Per RIM</th>
                        <th class="font-size-10 text-center">Per LB</th>
                        <th class="font-size-10 text-center">Material</th>
                        <th class="font-size-10 text-center">Bahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsRo) > 0):
                        $total_order_material = 0;
                        $total_order_bahan = 0;
                        $total_order_all = 0;
                        $grand_total = 0;
                        foreach($model->details as $val):
                            if($val->type_invoice == 2):
                                $total_order_material = $val->new_total_order_material;
                                $total_order_all += $total_order_material;
                                $total_order_bahan = $val->new_total_order_bahan;
                                $total_order_all += $total_order_bahan;
                                $grand_total = $val->new_grand_total;
                            endif;
                        endforeach; ?>
                        <?php foreach($model->itemsRo as $val):  ?>
                            <tr>
                                <td class="font-size-10 text-left">
                                    <?=(isset($val->item)) ? $val->item_code .' - '. $val->item->name : '-' ?>
                                    <br />
                                    <span class="font-size-10 text-muted"><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></span>
                                </td>
                                <td class="font-size-10 text-right">
                                    <?=(!empty($val->qty_order_1)) ? $val->qty_order_1 .' '. $val->um_1 : $val->qty_order_2 .' '. $val->um_2 ?>
                                </td>
                                <td class="font-size-10 text-right"><?=number_format($val->new_harga_jual_1).'.-' ?></td>
                                <td class="font-size-10 text-right"><?=number_format($val->new_harga_jual_2).'.-' ?></td>
                                <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                    <td class="font-size-10 text-right"><?=number_format($val->new_total_order).'.-' ?></td>
                                    <td></td>
                                <?php else: ?>
                                    <td></td>
                                    <td class="font-size-10 text-right"><?=number_format($val->new_total_order).'.-' ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="text-right mark-2" colspan="4">TOTAL:</td>
                            <td class="text-right mark-2"><?=number_format($total_order_material).'.-' ?></td>
                            <td class="text-right mark-2"><?=number_format($total_order_bahan).'.-' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right mark-3" colspan="5">TOTAL ORDER:</td>
                            <td class="text-right mark-3"><?=number_format($total_order_all).'.-' ?></td>
                        </tr>
                        <tr>
                            <td class="text-right mark-3" colspan="5"><strong>GRAND TOTAL:</strong></td>
                            <td class="text-right mark-3"><strong><?=number_format($grand_total).'.-' ?></strong></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="10">Data masih kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail request order -->
        <!-- biaya lain2 -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Biaya Lain2</h6>
            <hr />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom margin-top-10">
                <thead>
                    <tr>
                        <th class="font-size-10 text-center">Name</th>
                        <th class="font-size-10 text-center">Qty</th>
                        <th class="font-size-10 text-center">Harga (Rp)</th>
                        <th class="font-size-10 text-center">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsLain)): 
                        $total_biaya_lain = 0; ?>
                        <?php foreach($model->itemsLain as $val): 
                            $total_biaya_lain += $val->total_order; ?>
                            <tr>
                                <td class="text-left"><?=$val->typeOngkos($val->type_ongkos) ?></td>
                                <td class="text-center"><?=(!empty($val->unique_code)) ? $val->unique_code : '-' ?></td>
                                <td class="text-right"><?=number_format($val->harga_jual_1).'.-' ?></td>
                                <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td class="text-right mark-3" colspan="3">GRAND TOTAL:</td>
                            <td class="text-right mark-3"><?=number_format($total_biaya_lain).'.-' ?></td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="6">Data masih kosong.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /biaya lain2 -->
    </div>
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('sales-invoice[U]')): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Remark Harga</span>', ['update', 'no_invoice'=>$model->no_invoice], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
</div>