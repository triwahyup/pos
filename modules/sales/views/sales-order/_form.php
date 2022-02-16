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
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(false) ?>
                        <?php if(!$model->isNewRecord): ?>
                            <?= $form->field($model, 'code')->hiddenInput(['maxlength' => true])->label(false) ?>
                        <?php endif; ?>
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
                        <label>Minimal Order:</label>
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
                                    'readonly' => ($model->isNewRecord) ? true : false,
                                    'placeholder' => 'RIM',
                                    'value' => (!$model->isNewRecord) ? $itemTemp->qty_order_1 : '',
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempItem, 'qty_order_2')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'readonly' => ($model->isNewRecord) ? true : false,
                                    'maxlength' => 3,
                                    'placeholder' => 'LB',
                                    'value' => (!$model->isNewRecord) ? $itemTemp->qty_order_2 : '',
                                ]
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
                                    'data-temp' => true,
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
                        <?= $form->field($tempPotong, 'panjang[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 1,
                                    'placeholder' => 'P',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'lebar[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 1,
                                    'placeholder' => 'L',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'total_objek[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 1,
                                    'placeholder' => 'Objek',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'panjang[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 2,
                                    'placeholder' => 'P',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'lebar[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 2,
                                    'placeholder' => 'L',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'total_objek[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 2,
                                    'placeholder' => 'Objek',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'panjang[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 3,
                                    'placeholder' => 'P',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'lebar[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 3,
                                    'placeholder' => 'L',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'total_objek[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 3,
                                    'placeholder' => 'Objek',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'panjang[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 4,
                                    'placeholder' => 'P',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'lebar[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 4,
                                    'placeholder' => 'L',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
                        <?= $form->field($tempPotong, 'total_objek[]')->widget(MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true
                                ],
                                'options' => [
                                    'data-align' => 'text-right',
                                    'data-temp' => true,
                                    'data-urutan' => 4,
                                    'placeholder' => 'Objek',
                                    'disabled' => true,
                                ]
                            ])->label(false) ?>
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
                                    'data-temp' => true,
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
                        <?= $form->field($tempItem, 'satuan_ikat_code')->dropDownList($typeSatuan, [
                                'prompt'=>'Satuan Lb.Ikat', 'data-temp'=>true])->label(false) ?>
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
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempItem, 'lembar_ikat_um_1')->hiddenInput(['data-temp' => true, 'data-value' => 'DOS'])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
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
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempItem, 'lembar_ikat_um_2')->hiddenInput(['data-temp' => true, 'data-value' => 'IKAT'])->label(false) ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-right-0">
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
                                    'data-temp' => true,
                                    'readonly' => true,
                                ]
                            ])->label(false) ?>
                        <?= $form->field($tempItem, 'lembar_ikat_um_3')->hiddenInput(['data-temp' => true, 'data-value' => 'PCS'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 text-right">
                <button class="btn btn-success margin-bottom-20" data-button="create_temp">
                    <i class="fontello icon-plus"></i>
                    <span>Tambah Data Detail</span>
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
                                        <th class="text-center">PxL</th>
                                        <th class="text-center">Objek</th>
                                        <th class="text-center">Proses Produksi</th>
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
                    <!-- /detail item -->
                    <div class="col-lg-12 col-md-12 col-xs-12 margin-bottom-20"></div>
                    <!-- Ekspedisi -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>Ekspedisi:</strong>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'ekspedisi_name')->textInput(['maxlength' => true])->label(false) ?>
                        </div>
                    </div>
                    <!-- Biaya Pengiriman -->
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                            <strong>Biaya Pengiriman:</strong>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-right-0">
                            <?= $form->field($model, 'biaya_pengiriman')->widget(MaskedInput::className(), [
                                    'clientOptions' => [
                                        'alias' => 'decimal',
                                        'groupSeparator' => ',',
                                        'autoGroup' => true
                                    ],
                                    'options' => [
                                        'data-align' => 'text-right',
                                        'data-icons' => 'rupiah',
                                        'data-name' => 'iconbox',
                                    ]
                                ])->label(false) ?>
                        </div>
                    </div>
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
                            <?= $form->field($tempItem, 'bahan_item_name')->textInput(['placeholder' => 'Pilih material tekan F4', 'data-type'=>'bahan'])->label(false) ?>
                            <?= $form->field($tempItem, 'bahan_item_code')->hiddenInput()->label(false) ?>
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
                                        <th class="text-center" colspan="3">QTY</th>
                                        <th class="text-center" rowspan="2">Jenis</th>
                                        <th class="text-center" rowspan="2">Action</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">1</th>
                                        <th class="text-center">2</th>
                                        <th class="text-center">3</th>
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
                if(type == 'item'){
                    $("#tempsalesorderitem-qty_order_"+a).attr("readonly", false);
                }else{
                    $("#tempsalesorderitem-bahan_qty_order_"+a).attr("readonly", false);
                }
            }
            if(type == 'item'){
                $("#tempsalesorderitem-qty_order_1").val(20);
                $("#tempsalesorderitem-qty_order_2").val(0);
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
            init_temp_item();
        },
        complete: function(){
            popup.close();
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
            $("[id^=\"tempsalesorderdetail-lembar_ikat_\"]").attr("readonly", 1);
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
            $("[id^=\"tempsalesorderitem-bahan_\"]").val(null);
        },
        complete: function(){
            temp.destroy();
        }
    });
}

var timeOut = 3000;
$(document).ready(function(){
    $("body").off("keydown","#tempsalesorderitem-item_name")
    $("body").on("keydown","#tempsalesorderitem-item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("click","[data-id=\"popup\"] table > tbody tr[data-code]");
    $("body").on("click","[data-id=\"popup\"] table > tbody tr[data-code]", function(e){
        e.preventDefault();
        var data = $(this).data();
        select_item(data.code, data.type);
    });

    $("body").off("input","#tempsalesorderitem-qty_order_2");
    $("body").on("input","#tempsalesorderitem-qty_order_2", function(e){
        e.preventDefault();
        if($(this).val() >= 500){
            $(this).val(499);
        }else{
            $(this).val();
        }
    });

    $("body").off("keydown","#tempsalesorderitem-bahan_item_name")
    $("body").on("keydown","#tempsalesorderitem-bahan_item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("keydown","#tempsalesorderitem-item_name")
    $("body").on("keydown","#tempsalesorderitem-item_name", function(e){
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == KEY.F4){
            load_item($(this).data().type);
        }
    });

    $("body").off("input","#tempsalesorderitem-total_potong");
    $("body").on("input","#tempsalesorderitem-total_potong", function(e){
        e.preventDefault();
        if($(this).val() > 4){
            $(this).val(4);
        }else{
            $(this).val();
        }

        $("[id=\"tempsalesorderpotong-panjang\"]").prop("disabled", true);
        $("[id=\"tempsalesorderpotong-panjang\"]").val(null);
        $("[id=\"tempsalesorderpotong-lebar\"]").prop("disabled", true);
        $("[id=\"tempsalesorderpotong-lebar\"]").val(null);
        $("[id=\"tempsalesorderpotong-total_objek\"]").prop("disabled", true);
        $("[id=\"tempsalesorderpotong-total_objek\"]").val(null);
        for(var i=1;i<=$(this).val();i++){
            $("#tempsalesorderpotong-panjang[data-urutan=\""+i+"\"]").prop("disabled", false);
            $("#tempsalesorderpotong-lebar[data-urutan=\""+i+"\"]").prop("disabled", false);
            $("#tempsalesorderpotong-total_objek[data-urutan=\""+i+"\"]").prop("disabled", false);
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
});

$(function(){
    <?php if(!$model->isNewRecord): ?>
        init_temp_item();
        init_temp_bahan();
    <?php endif; ?>
});
</script>