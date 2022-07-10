<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
            <!-- HEADER -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Job</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- No. SO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. SO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_so', [
                                'template' => '{input}
                                    <span class="text-muted">'.$sorder->name .' - '.$sorder->customer->name.'</span>
                                    {error}{hint}'
                            ])->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
                <!-- No. SPK -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. SPK:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_spk')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
                <!-- No. Request -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. Request:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_request')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Tgl. SO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl. Request:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'tgl_request')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'dd-mm-yyyy',
                                'value' => (!$model->isNewRecord) ? date('d-m-Y', strtotime($model->tgl_request)) : date('d-m-Y'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]])->label(false) ?>
                    </div>
                </div>
                <!-- Keterangan -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Keterangan:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'keterangan')->textarea(['rows' => 2])->label(false) ?>
                    </div>
                </div>
            </div>
            <!-- /HEADER -->
            <!-- ITEM -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Item</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Item Name -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Material:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($temp, 'item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-type'=>'item', 'aria-required' => true])->label(false) ?>
                        <?= $form->field($temp, 'id')->hiddenInput()->label(false) ?>
                        <?= $form->field($temp, 'item_code')->hiddenInput()->label(false) ?>
                        <?= $form->field($temp, 'supplier_code')->hiddenInput()->label(false) ?>
                    </div>
                </div>
                <!-- Type Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Type QTY:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($temp, 'type_qty')->widget(Select2::classname(), [
                                'data' => [1=>'RIM', 2=>'LEMBAR'],
                                'options' => [
                                    'placeholder' => 'Pilih Type QTY',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Qty Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Qty:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($temp, 'qty_order_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                        <label id="satuan_qty_temp" class="font-size-14 margin-left-5 margin-top-5">RIM</label>
                    </div>
                </div>
            </div>
            <!-- Button Action -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-10 text-right">
                <button class="btn btn-success margin-bottom-20" data-button="create_temp" data-type="item">
                    <i class="fontello icon-plus"></i>
                    <span>Tambah Data Detail</span>
                </button>
                <button class="btn btn-success margin-bottom-20 hidden" data-button="change_temp">
                    <i class="fontello icon-plus"></i>
                    <span>Update Data Detail</span>
                </button>
                <button class="btn btn-danger margin-bottom-20 margin-left-5 hidden" data-button="cancel">
                    <i class="fontello icon-cancel"></i>
                    <span>Cancel</span>
                </button>
            </div>
            <!-- /ITEM -->
            <!-- VIEW DETAIL TEMP -->
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div render="detail" class="form-container padding-bottom-5">
                    <!-- detail item -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <h4>Detail Material</h4>
                        <hr />
                    </div>
                    <div data-render="detail-item">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center" colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-danger" colspan="8">Data masih kosong.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /detail item -->
                    <!-- form bahan pembantu -->
                    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                        <h4>Detail Bahan Pembantu</h4>
                        <hr />
                    </div>
                    <!-- Item Bahan -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>Item Bahan:</strong>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'bahan_item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-type'=>'bahan', 'data-temp'=>true])->label(false) ?>
                            <?= $form->field($temp, 'bahan_item_code')->hiddenInput(['data-temp'=>true])->label(false) ?>
                            <?= $form->field($temp, 'bahan_supplier_code')->hiddenInput(['data-temp'=>true])->label(false) ?>
                        </div>
                    </div>
                    <!-- QTY Bahan -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>Qty:</strong>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($temp, 'bahan_qty')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-temp' => true,
                                        'readonly' => true,
                                        'placeholder' => 'KG',
                                    ]
                                ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                        <button class="btn btn-success" data-button="create_temp_bahan">
                            <i class="fontello icon-plus"></i>
                            <span>Tambah Data Bahan</span>
                        </button>
                    </div>
                    <!-- /form bahan pembantu -->
                    <!-- detail bahan pembantu -->
                    <div data-render="detail-bahan">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <table class="table table-bordered table-custom margin-top-10">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Jenis</th>
                                        <th class="text-center">Action</th>
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
                    <!-- /detail bahan pembantu -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="form-group text-right">
                            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /VIEW DETAIL TEMP -->
        </div>
    <?php ActiveForm::end(); ?>
</div>
<div data-popup="popup"></div>
<script>
var timeOut = 3000;
$(document).ready(function(){

});
</script>