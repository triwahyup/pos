<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use app\modules\sales\models\TempSalesOrderProduksiDetail;

/**
 * This is the model class for table "temp_sales_order_detail".
 *
 * @property int $id
 * @property string|null $order_code
 * @property int|null $urutan
 * @property string|null $no_so
 * @property string|null $item_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $group_supplier_code
 * @property string|null $group_material_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $potong
 * @property int|null $objek
 * @property int|null $mesin
 * @property int|null $jumlah_warna
 * @property int|null $lembar_ikat
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
 * @property float|null $jumlah_lem
 * @property int|null $user_id
 */
class TempSalesOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'potong', 'objek', 'mesin', 'jumlah_warna', 'lembar_ikat', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'harga_cetak', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'jumlah_cetak', 'jumlah_objek', 'jumlah_lem', 'total_order'], 'number'],
            [['order_code', 'satuan_code', 'material_code', 'type_code', 'group_supplier_code', 'group_material_code'], 'string', 'max' => 3],
            [['no_so'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'no_so' => 'No So',
            'item_code' => 'Item Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'group_supplier_code' => 'Group Supplier Code',
            'group_material_code' => 'Group Material Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'potong' => 'Potong',
            'objek' => 'Objek',
            'mesin' => 'Mesin',
            'jumlah_warna' => 'Jumlah Warna',
            'lembar_ikat' => 'Lembar Ikat',
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
            'jumlah_lem' => 'Jumlah Lem',
            'user_id' => 'User ID',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getDetailsProduksi()
    {
        if(!empty($this->no_so)){
            return $this->hasMany(TempSalesOrderProduksiDetail::className(), ['no_so' => 'no_so', 'item_code' => 'item_code']);
        }else{
            return $this->hasMany(TempSalesOrderProduksiDetail::className(), ['item_code' => 'item_code', 'user_id'=> 'user_id']);
        }
    }
}
