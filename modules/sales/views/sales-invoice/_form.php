<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-invoice-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background" render="<?=(!$model->isNewRecord) ? 'detail' : '' ?>">
            <?php if($model->isNewRecord): ?>
                <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                    <h4>Filter Sales Order</h4>
                    <hr />
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <!-- No So -->
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <label>No. Sales Order:</label>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'no_so')->widget(Select2::classname(), [
                                    'data' => $listSalesOrder,
                                    'options' => [
                                        'placeholder' => 'Pilih No. Sales Order',
                                        'class' => 'select2',
                                    ],
                                ])->label(false) ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
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
            <?php endif; ?>
            <!-- VIEW DETAIL TEMP -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <div render="detail" class="form-container padding-bottom-5">
                    <!-- detail sales order -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <?php if($model->isNewRecord): ?>
                            <h4>Detail Sales Order</h4>
                        <?php else: ?>
                            <h6>Detail Sales Order</h6>
                        <?php endif; ?>
                        <hr />
                    </div>
                    <div data-render="detail-sales-order">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="font-size-10 text-center" rowspan="2">Item</th>
                                        <th class="font-size-10 text-center" rowspan="2">Qty</th>
                                        <th class="font-size-10 text-center" colspan="2">Harga (Rp)</th>
                                        <th class="font-size-10 text-center" colspan="3">Total Real Order (Rp)</th>
                                        <?php if(!$model->isNewRecord): ?>
                                            <th class="font-size-10 text-center" colspan="5">Remark Harga & Total Order (Rp)</th>
                                            <th class="font-size-10 text-center" rowspan="2">Action</th>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <th class="font-size-10 text-center">Per RIM</th>
                                        <th class="font-size-10 text-center">Per LB</th>
                                        <th class="font-size-10 text-center">Material</th>
                                        <th class="font-size-10 text-center">Bahan</th>
                                        <th class="font-size-10 text-center">Biaya Produksi</th>
                                        <?php if(!$model->isNewRecord): ?>
                                            <th class="font-size-10 text-center">Per RIM</th>
                                            <th class="font-size-10 text-center">Per LB</th>
                                            <th class="font-size-10 text-center">Material</th>
                                            <th class="font-size-10 text-center">Bahan</th>
                                            <th class="font-size-10 text-center">Biaya Produksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!$model->isNewRecord): ?>
                                        <?php if(count($model->itemsSo) > 0):
                                            $total_order_material = 0;
                                            $total_order_bahan = 0;
                                            $total_biaya_produksi = 0;
                                            $total_order_all = 0;
                                            $total_ppn = 0;
                                            $grand_total = 0;

                                            $new_total_order_material = 0;
                                            $new_total_order_bahan = 0;
                                            $new_total_biaya_produksi = 0;
                                            $new_total_order_all = 0;
                                            $new_total_ppn = 0;
                                            $new_grand_total = 0;
                                            foreach($model->details as $val):
                                                if($val->type_invoice == 1):
                                                    $total_order_material = $val->total_order_material;
                                                    $total_order_all += $total_order_material;
                                                    $total_order_bahan = $val->total_order_bahan;
                                                    $total_order_all += $total_order_bahan;
                                                    $total_biaya_produksi = $val->total_biaya_produksi;
                                                    $total_order_all += $total_biaya_produksi;
                                                    $total_ppn = $val->total_ppn;
                                                    $grand_total = $val->grand_total;

                                                    $new_total_order_material = $val->new_total_order_material;
                                                    $new_total_order_all += $new_total_order_material;
                                                    $new_total_order_bahan = $val->new_total_order_bahan;
                                                    $new_total_order_all += $new_total_order_bahan;
                                                    $new_total_biaya_produksi = $val->new_total_biaya_produksi;
                                                    $new_total_order_all += $new_total_biaya_produksi;
                                                    $new_total_ppn = $val->new_total_ppn;
                                                    $new_grand_total = $val->new_grand_total;
                                                endif;
                                            endforeach; ?>
                                            <?php foreach($model->itemsSo as $val): ?>
                                                <?php 
                                                    $remark_harga = (
                                                        $val->harga_jual_1 != $val->new_harga_jual_1 || 
                                                        $val->harga_jual_2 != $val->new_harga_jual_2
                                                    ) ? true : false;
                                                    $markingCss = $remark_harga ? 'mark-1' : '';
                                                ?>
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
                                                    <!-- REAL -->
                                                    <td class="font-size-10 text-right"><?=number_format($val->harga_jual_1).'.-' ?></td>
                                                    <td class="font-size-10 text-right"><?=number_format($val->harga_jual_2).'.-' ?></td>
                                                    <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                        <td></td><td></td>
                                                    <?php elseif($val->kode['name'] == \Yii::$app->params['TYPE_BAHAN_PB']): ?>
                                                        <td></td>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                        <td></td>
                                                    <?php else: ?>
                                                        <td></td><td></td>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                    <?php endif; ?>
                                                    <!-- /REAL -->
                                                    <!-- REMARK -->
                                                    <td class="font-size-10 text-right <?=$markingCss ?>">
                                                        <?=number_format($val->new_harga_jual_1).'.-' ?>
                                                    </td>
                                                    <td class="font-size-10 text-right <?=$markingCss ?>">
                                                        <?=number_format($val->new_harga_jual_2).'.-' ?>
                                                    </td>
                                                    <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                                        <td class="font-size-10 text-right <?=$markingCss ?>">
                                                            <?=number_format($val->new_total_order).'.-' ?>
                                                        </td>
                                                        <td></td><td></td>
                                                    <?php elseif($val->kode['name'] == \Yii::$app->params['TYPE_BAHAN_PB']): ?>
                                                        <td></td>
                                                        <td class="font-size-10 text-right <?=$markingCss ?>">
                                                            <?=number_format($val->new_total_order).'.-' ?>
                                                        </td>
                                                        <td></td>
                                                    <?php else: ?>
                                                        <td></td><td></td>
                                                        <td class="font-size-10 text-right <?=$markingCss ?>">
                                                            <?=number_format($val->new_total_order).'.-' ?>
                                                        </td>
                                                    <?php endif; ?>
                                                    <!-- /REMARK -->
                                                    <?php if(!$model->isNewRecord): ?>
                                                        <td class="text-center">
                                                            <button class="btn btn-warning btn-xs btn-sm"
                                                                data-button="popup" data-invoice="<?=$val->no_invoice ?>" data-type="<?=$val->type_invoice ?>" data-urutan="<?=$val->urutan ?>">
                                                                <i class="fontello icon-pencil"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="text-right mark-2" colspan="4">TOTAL:</td>
                                                <td class="text-right mark-2"><?=number_format($total_order_material).'.-' ?></td>
                                                <td class="text-right mark-2"><?=number_format($total_order_bahan).'.-' ?></td>
                                                <td class="text-right mark-2"><?=number_format($total_biaya_produksi).'.-' ?></td>
                                                <td class="text-right mark-2" colspan="2"></td>
                                                <td class="text-right mark-2"><?=number_format($new_total_order_material).'.-' ?></td>
                                                <td class="text-right mark-2"><?=number_format($new_total_order_bahan).'.-' ?></td>
                                                <td class="text-right mark-2"><?=number_format($new_total_biaya_produksi).'.-' ?></td>
                                                <td class="last-row"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-3" colspan="6">TOTAL ORDER:</td>
                                                <td class="text-right mark-3"><?=number_format($total_order_all).'.-' ?></td>
                                                <td class="text-right mark-3" colspan="4">TOTAL ORDER:</td>
                                                <td class="text-right mark-3"><?=number_format($new_total_order_all).'.-' ?></td>
                                                <td class="last-row"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-3" colspan="6">TOTAL PPN:</td>
                                                <td class="text-right mark-3"><?=number_format($total_ppn).'.-' ?></td>
                                                <td class="text-right mark-3" colspan="4">TOTAL PPN:</td>
                                                <td class="text-right mark-3"><?=number_format($new_total_ppn).'.-' ?></td>
                                                <td class="last-row"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-3" colspan="6">GRAND TOTAL:</td>
                                                <td class="text-right mark-3"><?=number_format($grand_total).'.-' ?></td>
                                                <td class="text-right mark-3" colspan="4">GRAND TOTAL:</td>
                                                <td class="text-right mark-3"><?=number_format($new_grand_total).'.-' ?></td>
                                                <td class="last-row"></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="text-danger" colspan="10">Data masih kosong.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail sales order -->
                    <!-- detail request order -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <?php if($model->isNewRecord): ?>
                            <h4>Detail Request Order</h4>
                        <?php else: ?>
                            <h6>Detail Request Order</h6>
                        <?php endif; ?>
                        <hr />
                    </div>
                    <div data-render="detail-request-order">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="font-size-10 text-center" rowspan="2">Item</th>
                                        <th class="font-size-10 text-center" rowspan="2">Qty</th>
                                        <th class="font-size-10 text-center" colspan="2">Harga (Rp)</th>
                                        <th class="font-size-10 text-center" colspan="2">Total Real Order (Rp)</th>
                                        <?php if(!$model->isNewRecord): ?>
                                            <th class="font-size-10 text-center" colspan="4">Remark Harga & Total Order (Rp)</th>
                                            <th class="font-size-10 text-center" rowspan="2">Action</th>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <th class="font-size-10 text-center">Per RIM</th>
                                        <th class="font-size-10 text-center">Per LB</th>
                                        <th class="font-size-10 text-center">Material</th>
                                        <th class="font-size-10 text-center">Bahan</th>
                                        <?php if(!$model->isNewRecord): ?>
                                            <th class="font-size-10 text-center">Per RIM</th>
                                            <th class="font-size-10 text-center">Per LB</th>
                                            <th class="font-size-10 text-center">Material</th>
                                            <th class="font-size-10 text-center">Bahan</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!$model->isNewRecord): ?>
                                        <?php if(count($model->itemsRo) > 0):
                                            $total_order_material = 0;
                                            $total_order_bahan = 0;
                                            $grand_total = 0;

                                            $new_total_order_material = 0;
                                            $new_total_order_bahan = 0;
                                            $new_grand_total = 0;
                                            foreach($model->details as $val):
                                                if($val->type_invoice == 2):
                                                    $total_order_material = $val->total_order_material;
                                                    $total_order_bahan = $val->total_order_bahan;
                                                    $grand_total = $val->grand_total;

                                                    $new_total_order_material = $val->new_total_order_material;
                                                    $new_total_order_bahan = $val->new_total_order_bahan;
                                                    $new_grand_total = $val->new_grand_total;
                                                endif;
                                            endforeach; ?>
                                            <?php foreach($model->itemsRo as $val):  ?>
                                                <?php
                                                    $remark_harga = (
                                                        $val->harga_jual_1 != $val->new_harga_jual_1 || 
                                                        $val->harga_jual_2 != $val->new_harga_jual_2
                                                    ) ? true : false;
                                                    $markingCss = $remark_harga ? 'mark-1' : '';
                                                ?>
                                                <tr>
                                                    <td class="font-size-10 text-left">
                                                        <?=(isset($val->item)) ? $val->item_code .' - '. $val->item->name : '-' ?>
                                                        <br />
                                                        <span class="font-size-10 text-muted"><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></span>
                                                    </td>
                                                    <td class="font-size-10 text-right">
                                                        <?=(!empty($val->qty_order_1)) ? $val->qty_order_1 .' '. $val->um_1 : $val->qty_order_2 .' '. $val->um_2 ?>
                                                    </td>
                                                    <!-- REAL -->
                                                    <td class="font-size-10 text-right"><?=number_format($val->harga_jual_1).'.-' ?></td>
                                                    <td class="font-size-10 text-right"><?=number_format($val->harga_jual_2).'.-' ?></td>
                                                    <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                        <td></td>
                                                    <?php elseif($val->kode['name'] == \Yii::$app->params['TYPE_BAHAN_PB']): ?>
                                                        <td></td>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                    <?php endif; ?>
                                                    <!-- /REAL -->
                                                    <!-- REMARK -->
                                                    <td class="font-size-10 text-right <?=$markingCss ?>">
                                                        <?=number_format($val->new_harga_jual_1).'.-' ?>
                                                    </td>
                                                    <td class="font-size-10 text-right <?=$markingCss ?>">
                                                        <?=number_format($val->new_harga_jual_2).'.-' ?>
                                                    </td>
                                                    <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                                        <td class="font-size-10 text-right <?=$markingCss ?>">
                                                            <?=number_format($val->new_total_order).'.-' ?>
                                                        </td>
                                                        <td></td>
                                                    <?php elseif($val->kode['name'] == \Yii::$app->params['TYPE_BAHAN_PB']): ?>
                                                        <td></td>
                                                        <td class="font-size-10 text-right <?=$markingCss ?>">
                                                            <?=number_format($val->new_total_order).'.-' ?>
                                                        </td>
                                                    <?php endif; ?>
                                                    <!-- /REMARK -->
                                                    <?php if(!$model->isNewRecord): ?>
                                                        <td class="text-center">
                                                            <button class="btn btn-warning btn-xs btn-sm"
                                                                data-button="popup" data-invoice="<?=$val->no_invoice ?>" data-type="<?=$val->type_invoice ?>" data-urutan="<?=$val->urutan ?>">
                                                                <i class="fontello icon-pencil"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="text-right mark-2" colspan="4">TOTAL:</td>
                                                <td class="text-right mark-2"><?=number_format($total_order_material).'.-' ?></td>
                                                <td class="text-right mark-2"><?=number_format($total_order_bahan).'.-' ?></td>
                                                <td class="text-right mark-2" colspan="2"></td>
                                                <td class="text-right mark-2"><?=number_format($new_total_order_material).'.-' ?></td>
                                                <td class="text-right mark-2"><?=number_format($new_total_order_bahan).'.-' ?></td>
                                                <td class="last-row"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-3" colspan="5"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right mark-3"><strong><?=number_format($grand_total).'.-' ?></strong></td>
                                                <td class="text-right mark-3" colspan="2"></td>
                                                <td class="text-right mark-3"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right mark-3"><strong><?=number_format($new_grand_total).'.-' ?></strong></td>
                                                <td class="last-row"></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="text-danger" colspan="10">Data masih kosong.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail request order -->
                    <!-- biaya lain2 -->
                    <?php if(!$model->isNewRecord): ?>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <h6>Detail Biaya Lain2</h6>
                            <hr />
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                            <button class="btn btn-success" data-button="popup_biaya_lain" data-invoice="<?=$model->no_invoice?>" data-type data-urutan>
                                <i class="fontello icon-plus"></i>
                                <span>Biaya Lain2</span>
                            </button>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="font-size-10 text-center">Name</th>
                                        <th class="font-size-10 text-center">Kode Refrensi</th>
                                        <th class="font-size-10 text-center">Harga (Rp)</th>
                                        <th class="font-size-10 text-center">Total (Rp)</th>
                                        <th class="font-size-10 text-center" rowspan="2">Action</th>
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
                                                <td class="text-center">
                                                    <button class="btn btn-warning btn-xs btn-sm"
                                                        data-button="popup_biaya_lain" data-invoice="<?=$val->no_invoice ?>" data-urutan="<?=$val->urutan ?>">
                                                        <i class="fontello icon-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-xs btn-sm"
                                                        data-button="delete" data-invoice="<?=$val->no_invoice ?>" data-urutan="<?=$val->urutan ?>">
                                                        <i class="fontello icon-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td class="text-right mark-3" colspan="3">GRAND TOTAL:</td>
                                            <td class="text-right mark-3"><?=number_format($total_biaya_lain).'.-' ?></td>
                                            <td class="last-row"></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td class="text-danger" colspan="6">Data masih kosong.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <!-- /biaya lain2 -->
                </div>
            </div>
            <!-- /VIEW DETAIL TEMP -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="form-group text-right">
                    <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
function detail_sales_order()
{
    $.ajax({
        url: "<?= Url::to(['sales-invoice/detail-sales-order']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-sales-order\"]").html(o.data);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

function detail_request_order()
{
    $.ajax({
        url: "<?= Url::to(['sales-invoice/detail-request-order']) ?>",
        type: "POST",
        data: $("#form").serialize(),
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-request-order\"]").html(o.data);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

function popup_update(el)
{
    var data = el.data();
    $.ajax({
        url: "<?= Url::to(['sales-invoice/popup-remark-harga']) ?>",
        type: "GET",
        data: {
            no_invoice: data.invoice,
            type_invoice: data.type,
            urutan: data.urutan
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'Form Remark Harga',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function update_harga()
{
    $.ajax({
        url: "<?= Url::to(['sales-invoice/update-harga']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: $("#form_remark").serialize(),
        success: function(data){
            location.reload();
        },
        complete: function(){}
    });
}

function popup_biaya_lain(el)
{
    var data = el.data();
    $.ajax({
        url: "<?= Url::to(['sales-invoice/popup-biaya-lain']) ?>",
        type: "GET",
        data: {
            no_invoice: data.invoice,
            type_invoice: data.type,
            urutan: data.urutan
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'Form Biaya Lain2',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function update_biaya_lain()
{
    $.ajax({
        url: "<?= Url::to(['sales-invoice/update-biaya-lain']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: $("#form_biaya_lain").serialize(),
        success: function(data){
            location.reload();
        },
        complete: function(){}
    });
}

function delete_biaya_lain(no_invoice, urutan)
{
    $.ajax({
        url: "<?= Url::to(['sales-invoice/delete-biaya-lain']) ?>",
        type: "GET",
        data: {
            no_invoice: no_invoice,
            urutan: urutan,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            location.reload();
        },
        complete: function(){}
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#salesinvoice-no_so").on("change","#salesinvoice-no_so", function(e){
        e.preventDefault();
        detail_sales_order();
        detail_request_order();
    });

    $("body").off("click","[data-button=\"popup\"]").on("click","[data-button=\"popup\"]", function(e){
        e.preventDefault();
        popup_update($(this));
    });

    $("body").off("click","[data-button=\"update\"]").on("click","[data-button=\"update\"]", function(e){
        e.preventDefault();
        update_harga();
    });

    $("body").off("click","[data-button=\"popup_biaya_lain\"]").on("click","[data-button=\"popup_biaya_lain\"]", function(e){
        e.preventDefault();
        popup_biaya_lain($(this));
    });

    $("body").off("click","[data-button=\"create_biaya\"]").on("click","[data-button=\"create_biaya\"]", function(e){
        e.preventDefault();
        var success = false;
        if(!$("#salesinvoiceitem-type_ongkos").val()){
            notification.open("danger", "Type Ongkos tidak boleh kosong", timeOut);
        }else{
            if($("#salesinvoiceitem-type_ongkos").val() != 1){
                if(!$("#salesinvoiceitem-unique_code").val()){
                    notification.open("danger", "Kode Refrensi tidak boleh kosong", timeOut);
                }else{
                    success = true;
                }
            }else{
                success = true;
            }
        }

        if(success){
            if($("#salesinvoiceitem-harga_jual_1").val() == 0){
                notification.open("danger", "Biaya tidak boleh kosong / 0", timeOut);
            }else{
                update_biaya_lain();
            }
        }
    });

    $("body").off("change","#salesinvoiceitem-type_ongkos").on("change","#salesinvoiceitem-type_ongkos", function(e){
        e.preventDefault();
        if($(this).val() != 1){
            $("#salesinvoiceitem-unique_code").attr("readonly", false);
        }else{
            $("#salesinvoiceitem-unique_code").attr("readonly", true);
        }
    });

    $("body").off("click","[data-button=\"delete\"]").on("click","[data-button=\"delete\"]", function(e){
        e.preventDefault();
        var data = $(this).data(),
            target = data.invoice+'-'+data.urutan;
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete",
			target: target,
		});
    });
    $("body").off("click","#delete").on("click","#delete", function(e){
        e.preventDefault();
        var target = $(this).attr("data-target");
        target = target.split('-');
        delete_biaya_lain(target[0], target[1]);
    });
});
</script>
<script src="<?= Url::to('js/plugin/jquery.mask.min.js') ?>"></script>