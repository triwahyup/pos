<?php
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-invoice-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
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
            <!-- VIEW DETAIL TEMP -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <div render="detail" class="form-container padding-bottom-5">
                    <!-- detail sales order -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <h4>Detail Sales Order</h4>
                        <hr />
                    </div>
                    <div data-render="detail-sales-order">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="font-size-10 text-center" rowspan="2">Item</th>
                                        <th class="font-size-10 text-center" rowspan="2">Qty</th>
                                        <th class="font-size-10 text-center" rowspan="2">Harga</th>
                                        <th class="font-size-10 text-center" colspan="3">Total Real Order (Rp)</th>
                                    </tr>
                                    <tr>
                                        <th class="font-size-10 text-center">Material</th>
                                        <th class="font-size-10 text-center">Bahan</th>
                                        <th class="font-size-10 text-center">Biaya Produksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger" colspan="10">Data masih kosong.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail sales order -->
                    <!-- detail request order -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <h4>Detail Request Order</h4>
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
                                    </tr>
                                    <tr>
                                        <th class="font-size-10 text-center">Per RIM</th>
                                        <th class="font-size-10 text-center">Per LB</th>
                                        <th class="font-size-10 text-center">Material</th>
                                        <th class="font-size-10 text-center">Bahan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger" colspan="10">Data masih kosong.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail request order -->
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

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#salesinvoice-no_so").on("change","#salesinvoice-no_so", function(e){
        e.preventDefault();
        detail_sales_order();
        detail_request_order();
    });
});
</script>