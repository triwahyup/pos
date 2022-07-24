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
        <div class="form-container no-background" render="detail">
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
                                <span><?=number_format($model->total_order_material).'.-' ?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                                <label>Total Order Bahan</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <span><?=number_format($model->total_order_bahan).'.-' ?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                                <label>Total Biaya Produksi</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <span><?=number_format($model->total_biaya_produksi).'.-' ?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                                <label>PPN (%)</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <span><?=number_format($model->total_ppn).'.-' ?></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 text-right margin-top-10">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0">
                                <label class="font-size-16">Grand Total</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                                <strong class="font-size-16"><?=number_format($model->grand_total).'.-' ?></strong>
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
                                        <th class="font-size-10 text-center" colspan="2">Harga</th>
                                        <th class="font-size-10 text-center" colspan="3">Total Real Order (Rp)</th>
                                        <?php if(!$model->isNewRecord): ?>
                                            <th class="font-size-10 text-center" rowspan="2">Action</th>
                                        <?php endif; ?>
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
                                    <?php if(!$model->isNewRecord): ?>
                                        <?php if(count($model->itemsSo) > 0):
                                            $total_order_material = 0;
                                            $total_order_bahan = 0;
                                            $total_biaya_produksi = 0;
                                            $total_order_all = 0;
                                            $total_ppn = 0;
                                            $grand_total = 0;
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
                                                    <?php if(!$model->isNewRecord): ?>
                                                        <td class="text-center">
                                                            <button class="btn btn-warning btn-xs btn-sm"
                                                                data-button="update" data-invoice="<?=$val->no_invoice ?>" data-type="<?=$val->type_invoice ?>" data-urutan="<?=$val->urutan ?>">
                                                                <i class="fontello icon-pencil"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="text-right mark" colspan="5">TOTAL:</td>
                                                <td class="text-right mark"><?=number_format($total_order_material).'.-' ?></td>
                                                <td class="text-right mark"><?=number_format($total_order_bahan).'.-' ?></td>
                                                <td class="text-right mark"><?=number_format($total_biaya_produksi).'.-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-2" colspan="7">TOTAL ORDER:</td>
                                                <td class="text-right mark-2"><?=number_format($total_order_all).'.-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-2" colspan="7">TOTAL PPN:</td>
                                                <td class="text-right mark-2"><?=number_format($total_ppn).'.-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-2" colspan="7"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right mark-2"><strong><?=number_format($grand_total).'.-' ?></strong></td>
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
                                            <th class="font-size-10 text-center" rowspan="2">Action</th>
                                        <?php endif; ?>
                                    </tr>
                                    <tr>
                                        <th class="font-size-10 text-center">Per RIM</th>
                                        <th class="font-size-10 text-center">Per LB</th>
                                        <th class="font-size-10 text-center">Material</th>
                                        <th class="font-size-10 text-center">Bahan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!$model->isNewRecord): ?>
                                        <?php if(count($model->itemsRo) > 0):
                                            $total_order_material = 0;
                                            $total_order_bahan = 0;
                                            $total_order_all = 0;
                                            $grand_total = 0;
                                            foreach($model->details as $val):
                                                if($val->type_invoice == 2):
                                                    $total_order_material = $val->total_order_material;
                                                    $total_order_all += $total_order_material;
                                                    $total_order_bahan = $val->total_order_bahan;
                                                    $total_order_all += $total_order_bahan;
                                                    $grand_total = $val->grand_total;
                                                endif;
                                            endforeach; ?>
                                            <?php foreach($model->itemsRo as $val):  ?>
                                                <tr>
                                                    <td class="font-size-10 text-left">
                                                        <?=(isset($val->item)) ? $val->item_code .' - '. $val->item->name : '-' ?>
                                                        <br />
                                                        <span class="font-size-10 text-muted"><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></span>
                                                    </td>
                                                    <td class="font-size-10 text-right"><?=(!empty($val->qty_order_1)) ? $val->qty_order_1 .' '. $val->um_1 : $val->qty_order_2 .' '. $val->um_2 ?></td>
                                                    <td class="font-size-10 text-right"><?=number_format($val->harga_jual_1).'.-' ?></td>
                                                    <td class="font-size-10 text-right"><?=number_format($val->harga_jual_2).'.-' ?></td>
                                                    <?php if($val->kode['name'] == \Yii::$app->params['TYPE_KERTAS']): ?>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                        <td></td>
                                                    <?php else: ?>
                                                        <td></td>
                                                        <td class="font-size-10 text-right"><?=number_format($val->total_order).'.-' ?></td>
                                                    <?php endif; ?>
                                                    <?php if(!$model->isNewRecord): ?>
                                                        <td class="text-center">
                                                            <button class="btn btn-warning btn-xs btn-sm"
                                                                data-button="update" data-invoice="<?=$val->no_invoice ?>" data-type="<?=$val->type_invoice ?>" data-urutan="<?=$val->urutan ?>">
                                                                <i class="fontello icon-pencil"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td class="text-right mark" colspan="5">TOTAL:</td>
                                                <td class="text-right mark"><?=number_format($total_order_material).'.-' ?></td>
                                                <td class="text-right mark"><?=number_format($total_order_bahan).'.-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-2" colspan="6">TOTAL ORDER:</td>
                                                <td class="text-right mark-2"><?=number_format($total_order_all).'.-' ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right mark-2" colspan="6"><strong>GRAND TOTAL:</strong></td>
                                                <td class="text-right mark-2"><strong><?=number_format($grand_total).'.-' ?></strong></td>
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
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="font-size-10 text-center">Name</th>
                                        <th class="font-size-10 text-center">Qty</th>
                                        <th class="font-size-10 text-center">Harga (Rp)</th>
                                        <th class="font-size-10 text-center">Total (Rp)</th>
                                        <th class="font-size-10 text-center" rowspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger" colspan="6">Data masih kosong.</td>
                                    </tr>
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
        url: "<?= Url::to(['sales-order/update-temp']) ?>",
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

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#salesinvoice-no_so").on("change","#salesinvoice-no_so", function(e){
        e.preventDefault();
        detail_sales_order();
        detail_request_order();
    });

    $("body").off("click","[data-button=\"update\"]").on("click","[data-button=\"update\"]", function(e){
        e.preventDefault();
        popup_update($(this));
    });
});
</script>
<script src="<?= Url::to('js/plugin/jquery.mask.min.js') ?>"></script>