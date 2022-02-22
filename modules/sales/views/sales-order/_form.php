<?php
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\SalesOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-order-form">
    <?php $form = ActiveForm::begin(['id'=>'form']); ?>
        <div class="form-container no-background">
            <!-- HEADER -->
            <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
                <h4>Detail Job</h4>
                <hr />
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Tgl. SO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl SO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'tgl_so')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'dd-mm-yyyy',
                                'value' => (!$model->isNewRecord) ? date('d-m-Y', strtotime($model->tgl_so)) : date('d-m-Y'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]])->label(false) ?>
                    </div>
                </div>
                <!-- Customer -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Customer:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'customer_code')->widget(Select2::classname(), [
                                'data' => $customer,
                                'options' => [
                                    'placeholder' => 'Pilih Customer',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Nama Job -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Nama Job:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Repeat Order tekan F4'])->label(false) ?>
                        <?php if(!$model->isNewRecord): ?>
                            <?= $form->field($model, 'code')->hiddenInput(['maxlength' => true])->label(false) ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Term In -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Term In:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'term_in', [
                                'template' => '{input}<span id="term_in" class="margin-bottom-10"></span>{error}{hint}'
                            ])->textInput(['data-align' => 'text-right'])->label(false) ?>
                    </div>
                </div>
                <!--  Deadline-->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Deadline:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'deadline')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'dd-mm-yyyy',
                                'value' => (!$model->isNewRecord) ? date('d-m-Y', strtotime($model->deadline)) : date('d-m-Y'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]])->label(false) ?>
                    </div>
                </div>
                <!-- Type Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Type Order:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'type_order')->widget(Select2::classname(), [
                                'data' => [1=>'Produksi', 2=>'Jasa'],
                                'options' => [
                                    'placeholder' => 'Pilih Type Order',
                                    'class' => 'select2',
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- No. PO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>No. PO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'no_po')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>
                <!-- Tgl. PO -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Tgl PO:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'tgl_po')->widget(DatePicker::classname(), [
                            'type' => DatePicker::TYPE_INPUT,
                            'options' => [
                                'placeholder' => 'dd-mm-yyyy',
                                'value' => (!$model->isNewRecord) ? date('d-m-Y', strtotime($model->tgl_po)) : date('d-m-Y'),
                            ],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                            ]])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Minimal Order -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>QTY Order (Plano):</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'qty_order_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'value' => (!$model->isNewRecord) ? $tempItem->itemMaterial->qty_order_1 : '',
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0">
                        <span class=" font-size-10 text-muted margin-left-5 margin-top-5">RIM</span>
                    </div>
                </div>
                <!-- Ekspedisi Flag -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Pengambilan Barang:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'ekspedisi_flag')->dropDownList(['0' => 'Kirim', '1' => 'Ekspedisi'])->label(false) ?>
                    </div>
                </div>
                <!-- Ekspedisi -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Ekspedisi:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'ekspedisi_code')->widget(Select2::classname(), [
                                'data' => $ekspedisi,
                                'options' => [
                                    'placeholder' => 'Pilih Ekspedisi',
                                    'class' => 'select2',
                                    'readonly' => true
                                ],
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Up Produksi -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Up Produksi:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'up_produksi')->dropDownList(['5' => '5%', '10' => '10%'], ['prompt'=>'Produksi Up (%)'])->label(false) ?>
                    </div>
                </div>
                <!-- PPN -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>PPN:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($model, 'ppn')->textInput()->label(false) ?>
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
                <?php if(!$model->isNewRecord): ?>
                    <?= $form->field($tempItem, 'id')->hiddenInput()->label(false) ?>
                    <?= $form->field($tempItem, 'code')->hiddenInput()->label(false) ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Item Name -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Material:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-type'=>'item'])->label(false) ?>
                        <?= $form->field($tempItem, 'item_code')->hiddenInput()->label(false) ?>
                    </div>
                </div>
                <!-- Total Potong -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Total Potong:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'total_potong')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- PxL / Objek -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Potong (PxL) / Objek:</label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'panjang')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'placeholder' => 'P',
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-5 padding-right-0">
                        <?= $form->field($tempPotong, 'lebar')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'placeholder' => 'L',
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-5 padding-right-0">
                        <?= $form->field($tempPotong, 'objek')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'placeholder' => 'Objek',
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-5 padding-right-0">
                        <button class="btn btn-default margin-bottom-20" data-button="create_potong">
                            <i class="fontello icon-plus"></i>
                            <span class="margin-left-15"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12">
                <!-- Warna -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0  padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Total Warna:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'total_warna')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' =>  'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right', 
                                    'placeholder' => 'Total Warna'
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <!-- Lb. Ikat -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0  padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Lb.Ikat:</label>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'satuan_ikat_code')->dropDownList($typeSatuan, ['prompt'=>'Satuan Lb.Ikat'])->label(false) ?>
                    </div>
                </div>
                <!-- Satuan Lb. Ikat -->
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0  padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <label>Satuan Lb.Ikat:</label>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'lembar_ikat_1')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-um' => 'DOS',
                                    'placeholder' => 'DOS',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempItem, 'lembar_ikat_um_1')->hiddenInput(['data-value' => 'DOS'])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-5 padding-right-0">
                        <?= $form->field($tempItem, 'lembar_ikat_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-um' => 'IKAT',
                                    'placeholder' => 'IKAT',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempItem, 'lembar_ikat_um_2')->hiddenInput(['data-value' => 'IKAT'])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-5 padding-right-0">
                        <?= $form->field($tempItem, 'lembar_ikat_3')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'placeholder' => 'PCS',
                                    'data-um' => 'PCS',
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempItem, 'lembar_ikat_um_3')->hiddenInput(['data-value' => 'PCS'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <button class="btn btn-success margin-top-20 margin-bottom-20" data-button="create_temp" data-type="item">
                    <i class="fontello icon-plus"></i>
                    <span>Tambah Data Detail</span>
                </button>
                <button class="btn btn-success margin-bottom-20 hidden" data-button="change">
                    <i class="fontello icon-plus"></i>
                    <span>Update Data Detail</span>
                </button>
                <button class="btn btn-danger margin-bottom-20 margin-left-5 hidden" data-button="batal">
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
                    <div data-render="detail-item"></div>
                    <!-- /detail item -->
                    <!-- detail potong -->
                    <div data-render="detail-potong">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 padding-right-0">
                                <table class="table table-bordered table-custom margin-top-10">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">PxL</th>
                                            <th class="text-center">Objek</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-danger" colspan="5">Data masih kosong.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 padding-right-0">
                                <table class="table table-bordered table-custom margin-top-10">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th class="text-center">Proses Produksi</th>
                                            <th class="text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-danger" colspan="5">Data masih kosong.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /detail potong -->
                    <div class="col-lg-12 col-md-12 col-xs-12 margin-bottom-20"></div>
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
                            <?= $form->field($tempItem, 'bahan_item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-type'=>'bahan', 'data-temp'=>true])->label(false) ?>
                            <?= $form->field($tempItem, 'bahan_item_code')->hiddenInput(['data-temp'=>true])->label(false) ?>
                        </div>
                    </div>
                    <!-- QTY Bahan -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>QTY:</strong>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($tempItem, 'bahan_qty_order_1')->widget(MaskedInput::className(), [
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
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($tempItem, 'bahan_qty_order_2')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-temp' => true,
                                        'readonly' => true,
                                        'maxlength' => 3,
                                        'placeholder' => 'G',
                                    ]
                                ])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                        <button class="btn btn-success" data-button="create_temp">
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
                                        <th class="text-center" rowspan="2">No.</th>
                                        <th class="text-center" colspan="2">Item</th>
                                        <th class="text-center" colspan="2">QTY</th>
                                        <th class="text-center" rowspan="2">Jenis</th>
                                        <th class="text-center" rowspan="2">Action</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Um 1</th>
                                        <th class="text-center">Um 2</th>
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
function onChangeTermIn(customer_code, tgl_so)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/on-change-term-in'])?>",
		type: "GET",
        data: {
            customer_code: customer_code,
            tgl_so: tgl_so,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#salesorder-term_in").val(o.term_in);
            $("#term_in").html(o.tgl_tempo);
        },
        complete: function(){}
    });
}

function onInputTermIn(term_in, tgl_so)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/on-input-term-in'])?>",
		type: "GET",
        data: {
            term_in: term_in,
            tgl_so: tgl_so,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("#term_in").html(o.tgl_tempo);
        },
        complete: function(){}
    });
}

function type_order(type)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/type-order'])?>",
		type: "GET",
        data: {
            type: type,
        },
        dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#tempsalesorderitem-"+index).val(value);
            });
        },
        complete: function(){}
    });
}

function load_order()
{
    customer_code = $("#salesorder-customer_code").val();
    $.ajax({
        url: "<?=Url::to(['sales-order/list-order'])?>",
		type: "POST",
        data: {
            customer_code: customer_code
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Job Order',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function search_order(code)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/search-order'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            popup.close();
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Job Order',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function select_order(code)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/select-order'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#salesorder-"+index)
                    .not("#salesorder-tgl_so").not("#salesorder-tgl_po").not("#salesorder-no_po")
                    .not("#salesorder-deadline").val(value).trigger("change");
                $("#tempsalesorderitem-"+index).val(value);
            });
            isNotNewRecord();
        },
        complete: function(){
            popup.close();
        }
    });
}

function load_item(type)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/list-item'])?>",
		type: "GET",
        data: {
            type: type,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (data){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function search_item(code, type)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/search-item'])?>",
		type: "POST",
        data: {
            code: code,
            type: type,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            popup.close();
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Data Material',
				styleOptions: {
					width: 600
				}
			});
        },
        complete: function(){}
    });
}

function select_item(code, type)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/select-item'])?>",
		type: "POST",
        data: {
            code: code,
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                if(type == 'item'){
                    $("#tempsalesorderitem-"+index).val(value);
                }else{
                    $("#tempsalesorderitem-bahan_"+index).val(value);
                }
            });
            for(var a=1;a<=o.composite;a++){
                if(type == 'bahan'){
                    $("#tempsalesorderitem-bahan_qty_order_"+a).attr("readonly", false);
                }
            }
        },
        complete: function(){
            popup.close();
        }
    });
}

function create_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/create-temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: $("#form").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                init_temp_item();
                init_temp_bahan();
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function get_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/get-temp']) ?>",
        type: "GET",
        dataType: "text",
        data: {
            id: id
        },
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        success: function(data){
            var o = $.parseJSON(data);
            $.each(o, function(index, value){
                $("#tempsalesorderitem-"+index).val(value);
            });
            if(o.lembar_ikat_1 != 0){
                $("#tempsalesorderitem-lembar_ikat_1").attr("readonly", false);
            }else{
                $("#tempsalesorderitem-lembar_ikat_1").val(null);
            }

            if(o.lembar_ikat_2 != 0){
                $("#tempsalesorderitem-lembar_ikat_2").attr("readonly", false);
            }else{
                $("#tempsalesorderitem-lembar_ikat_2").val(null);
            }

            if(o.lembar_ikat_3 != 0){
                $("#tempsalesorderitem-lembar_ikat_3").attr("readonly", false);
            }else{
                $("#tempsalesorderitem-lembar_ikat_3").val(null);
            }
        },
        complete: function(){
            dataButtonHidden(1);
        }
    });
}

function update_temp(el)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/update-temp']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            el.loader("load");
        },
        data: $("#form").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            $("[data-button=\"batal\"]").trigger("click");
            init_temp_item();
        },
        complete: function(){
            el.loader("destroy");
        }
    });
}

function delete_temp(id)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/delete-temp']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            loading.open("loading bars");
        },
        data: {
            id: id
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            init_temp_item();
            init_temp_bahan();
            init_temp_potong();
            popup.close();
        },
        complete: function(){
			loading.close();
        }
    });
}

function load_proses(id)
{
    $.ajax({
        url: "<?=Url::to(['sales-order/list-proses'])?>",
		type: "GET",
        data: {
            id: id
        },
		dataType: "text",
        error: function(xhr, status, error) {},
		beforeSend: function (){},
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-popup=\"popup\"]").html(o.data);
            $("[data-popup=\"popup\"]").popup("open", {
				container: "popup",
				title: 'List Proses Produksi',
				styleOptions: {
					width: 300
				}
			});
        },
        complete: function(){}
    });
}

function create_proses()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/create-proses']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: $("#form_biaya").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            if(!o.success == true){
                notification.open("danger", o.message, timeOut);
            }
            init_temp_potong();
        },
        complete: function(){
            popup.close();
        }
    });
}

function create_potong()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/create-potong']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){},
        data: $("#form").serialize(),
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                init_temp_potong();
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
        },
        complete: function(){
            popup.close();
        }
    });
}

function delete_potong(id)
{
    $.ajax({
        url: "<?= Url::to(['sales-order/delete-potong']) ?>",
        type: "GET",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function(){
            loading.open("loading bars");
        },
        data: {
            id: id
        },
        success: function(data){
            var o = $.parseJSON(data);
            if(o.success == true){
                notification.open("success", o.message, timeOut);
            }else{
                notification.open("danger", o.message, timeOut);
            }
            init_temp_potong();
            popup.close();
        },
        complete: function(){
			loading.close();
        }
    });
}

function init_temp_item()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/temp-item']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-item\"]").html(o.model);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

function init_temp_bahan()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/temp-bahan']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-bahan\"] table > tbody").html(o.model);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

function init_temp_potong()
{
    $.ajax({
        url: "<?= Url::to(['sales-order/temp-potong']) ?>",
        type: "POST",
        dataType: "text",
        error: function(xhr, status, error) {},
        beforeSend: function() {
            temp.destroy();
        },
        success: function(data){
            var o = $.parseJSON(data);
            $("[data-render=\"detail-potong\"]").html(o.model);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("change","#salesorder-type_order").on("change","#salesorder-type_order", function(e){
        e.preventDefault();
        type_order($(this).val());
    });

    $("body").off("change","#salesorder-ekspedisi_flag").on("change","#salesorder-ekspedisi_flag", function(e){
        e.preventDefault();
        if($(this).val() == 1){
            $("#salesorder-ekspedisi_code").attr("readonly", false);
        }else{
            $("#salesorder-ekspedisi_code").attr("readonly", true);
        }
        $("#salesorder-ekspedisi_code").val(null).trigger("change");
    });

    /**  TERM IN */
    $("body").off("change","#salesorder-customer_code").on("change","#salesorder-customer_code", function(e){
        e.preventDefault();
        onChangeTermIn($(this).val(), $("#salesorder-tgl_so").val());
    });

    $("body").off("input","#salesorder-term_in").on("input","#salesorder-term_in", function(e){
        e.preventDefault();
        onInputTermIn($(this).val(), $("#salesorder-tgl_so").val());
    });
    /** END TERM IN */

    /** JOB */
    $("body").off("keydown","#salesorder-name").on("keydown","#salesorder-name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        <?php if($model->isNewRecord): ?>
            if(key == KEY.F4) load_order();
        <?php endif; ?>
    });

    $("body").off("click","table[data-table=\"sales_order\"] > tbody tr[data-code]");
    $("body").on("click","table[data-table=\"sales_order\"] > tbody tr[data-code]", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_order(data.code);
    });
    /** END JOB */

    /** LOAD ITEM MATERIAL & BAHAN */
    $("body").off("keydown","#tempsalesorderitem-item_name")
    $("body").on("keydown","#tempsalesorderitem-item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("keydown","#tempsalesorderitem-bahan_item_name")
    $("body").on("keydown","#tempsalesorderitem-bahan_item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("click","table[data-table=\"master_item_material\"] > tbody tr[data-code]");
    $("body").on("click","table[data-table=\"master_item_material\"] > tbody tr[data-code]", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_item(data.code, data.type);
    });
    /** END LOAD ITEM MATERIAL & BAHAN */

    /** POTONG TEMP */
    $("body").off("click","[data-button=\"create_potong\"]").on("click","[data-button=\"create_potong\"]", function(e){
        e.preventDefault();
        success = true;
        $.each($("[aria-required]:not([readonly])"), function(index, element){
            var a = $(element).attr("id").split("-"),
                b = a[1].replace("_", " ");
            if(!$(element).val()){
                success = false;
                errorMsg = parsing.toUpper(b, 2) +' cannot be blank.';
                if($(element).parent().hasClass("input-container")){
                    $(element).parent(".input-container").parent().removeClass("has-error").addClass("has-error");
                    $(element).parent(".input-container").siblings("[class=\"help-block\"]").text(errorMsg);
                }else{
                    $(element).parent().removeClass("has-error").addClass("has-error");
                    $(element).siblings("[class=\"help-block\"]").text(errorMsg);
                }
            }
        });
        if(success){
            create_potong($(this));
        }
    });

    $("body").off("click","[data-button=\"delete_potong\"]");
    $("body").on("click","[data-button=\"delete_potong\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete_potong",
			target: data.id,
		});
    });
    $("body").off("click","#delete_potong").on("click","#delete_potong", function(e){
        e.preventDefault();
        delete_potong($(this).attr("data-target"));
    });
    /** END POTONG TEMP */

    /** ITEM TEMP */
    $("body").off("input","#tempsalesorderitem-qty_order_2");
    $("body").on("input","#tempsalesorderitem-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

    $("body").off("change","#tempsalesorderitem-satuan_ikat_code");
    $("body").on("change","#tempsalesorderitem-satuan_ikat_code", function(e){
        e.preventDefault();
        var text = $(this).find("option:selected").text();
        $("[data-um]").attr("readonly", true);
        $("[data-value]").val(null);
        $.each(text.split("-"), function(index, value){
            $("[data-um=\""+value+"\"]").attr("readonly", false);
            $("[data-value=\""+value+"\"]").val(value);
        });
    });

    $("body").off("click","[data-button=\"create_temp\"]").on("click","[data-button=\"create_temp\"]", function(e){
        e.preventDefault();
        success = true;
        $.each($("[aria-required]:not([readonly])"), function(index, element){
            var a = $(element).attr("id").split("-"),
                b = a[1].replace("_", " ");
            if(!$(element).val()){
                success = false;
                errorMsg = parsing.toUpper(b, 2) +' cannot be blank.';
                if($(element).parent().hasClass("input-container")){
                    $(element).parent(".input-container").parent().removeClass("has-error").addClass("has-error");
                    $(element).parent(".input-container").siblings("[class=\"help-block\"]").text(errorMsg);
                }else{
                    $(element).parent().removeClass("has-error").addClass("has-error");
                    $(element).siblings("[class=\"help-block\"]").text(errorMsg);
                }
            }
        });
        if(success){
            create_temp($(this));
        }
    });

    $("body").off("click","[data-button=\"update_temp\"]").on("click","[data-button=\"update_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        get_temp(data.id);
    });
    $("body").off("click","[data-button=\"change\"]").on("click","[data-button=\"change\"]", function(e){
        e.preventDefault();
        update_temp($(this));
    });
    $("body").off("click","[data-button=\"batal\"]").on("click","[data-button=\"batal\"]", function(e){
        e.preventDefault();
        $("[id^=\"tempsalesorderitem-\"]")
            .not("#tempsalesorderitem-qty_order_1").not("#tempsalesorderitem-qty_order_2").val(null);
        dataButtonHidden();
    });

    $("body").off("click","[data-button=\"delete_temp\"]");
    $("body").on("click","[data-button=\"delete_temp\"]", function(e){
        e.preventDefault();
        var data = $(this).data();
        popup.open("confirm", {
			message: "Apakah anda yakin ingin menghapus data ini ?",
			selector: "delete_temp",
			target: data.id,
		});
    });
    $("body").off("click","#delete_temp").on("click","#delete_temp", function(e){
        e.preventDefault();
        delete_temp($(this).attr("data-target"));
    });
    /** END ITEM TEMP */

    /** PROSES TEMP */
    $("body").off("click","[data-button=\"create_proses_temp\"]");
    $("body").on("click","[data-button=\"create_proses_temp\"]", function(e){
        e.preventDefault();
        data = $(this).data();
        load_proses(data.id);
    });

    $("body").off("click","[data-button=\"create_proses\"]").on("click","[data-button=\"create_proses\"]", function(e){
        e.preventDefault();
        create_proses();
    });
    /** END PROSES TEMP */
});

var isNotNewRecord = function() {
    init_temp_item();
    init_temp_bahan();
    init_temp_potong();

    onInputTermIn($("#salesorder-term_in").val(), $("#salesorder-tgl_so").val());
    $("[id^=\"tempsalesorderitem-\"]:not([id^=\"tempsalesorderitem-qty_order_\"])").val(null)
}
var dataButtonHidden = function(type) {
    if(type == 1){
        $("[data-button=\"create_temp\"]").removeClass("hidden").addClass("hidden");
        $("[data-button=\"change\"]").removeClass("hidden");
        $("[data-button=\"batal\"]").removeClass("hidden");
    }else{
        $("[data-button=\"create_temp\"]").removeClass("hidden");
        $("[data-button=\"change\"]").removeClass("hidden").addClass("hidden");
        $("[data-button=\"batal\"]").removeClass("hidden").addClass("hidden");
    }
}
$(function(){
    <?php if(!$model->isNewRecord): ?>
        isNotNewRecord();
    <?php endif; ?>
});
</script>