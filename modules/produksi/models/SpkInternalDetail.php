<?php

namespace app\modules\produksi\models;

use Yii;

/**
 * This is the model class for table "spk_internal_detail".
 *
 * @property string $no_spk
 * @property int $urutan
 * @property string $order_code
 * @property string|null $item_code
 * @property string|null $satuan_code
 * @property string|null $satuan_ikat_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $group_supplier_code
 * @property string|null $group_material_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_potong
 * @property int|null $total_objek
 * @property int|null $total_warna
 * @property int|null $lembar_ikat_1
 * @property int|null $lembar_ikat_2
 * @property int|null $lembar_ikat_3
 * @property string|null $lembar_ikat_um_1
 * @property string|null $lembar_ikat_um_2
 * @property string|null $lembar_ikat_um_3
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property float|null $harga_cetak
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $jumlah_cetak
 * @property float|null $jumlah_objek
 * @property float|null $total_order
 * @property int|null $status_produksi
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkInternalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_internal_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_spk', 'urutan', 'order_code'], 'required'],
            [['urutan', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'status_produksi', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'harga_cetak', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'jumlah_cetak', 'jumlah_objek', 'total_order'], 'number'],
            [['no_spk'], 'string', 'max' => 12],
            [['order_code', 'satuan_code', 'satuan_ikat_code', 'material_code', 'type_code', 'group_supplier_code', 'group_material_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3', 'um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_spk' => 'No Spk',
            'urutan' => 'Urutan',
            'order_code' => 'Order Code',
            'item_code' => 'Item Code',
            'satuan_code' => 'Satuan Code',
            'satuan_ikat_code' => 'Satuan Ikat Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'group_supplier_code' => 'Group Supplier Code',
            'group_material_code' => 'Group Material Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Total Potong',
            'total_objek' => 'Total Objek',
            'total_warna' => 'Total Warna',
            'lembar_ikat_1' => 'Lembar Ikat 1',
            'lembar_ikat_2' => 'Lembar Ikat 2',
            'lembar_ikat_3' => 'Lembar Ikat 3',
            'lembar_ikat_um_1' => 'Lembar Ikat Um 1',
            'lembar_ikat_um_2' => 'Lembar Ikat Um 2',
            'lembar_ikat_um_3' => 'Lembar Ikat Um 3',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'harga_cetak' => 'Harga Cetak',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'jumlah_cetak' => 'Jumlah Cetak',
            'jumlah_objek' => 'Jumlah Objek',
            'total_order' => 'Total Order',
            'status_produksi' => 'Status Produksi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
