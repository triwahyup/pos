<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use app\modules\sales\models\SalesOrderPotong;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_item".
 *
 * @property string $code
 * @property int $urutan
 * @property string $item_code
 * @property string $supplier_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $konversi_1
 * @property float|null $konversi_2
 * @property float|null $konversi_3
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property string|null $satuan_ikat_code
 * @property int|null $lembar_ikat_1
 * @property int|null $lembar_ikat_2
 * @property int|null $lembar_ikat_3
 * @property string|null $lembar_ikat_um_1
 * @property string|null $lembar_ikat_um_2
 * @property string|null $lembar_ikat_um_3
 * @property int|null $total_potong
 * @property int|null $total_warna
 * @property float|null $total_order
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_item';
    }

    public function behaviors()
	{
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'urutan', 'item_code', 'supplier_code'], 'required'],
            [['urutan', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'total_potong', 'total_warna', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'qty_up', 'konversi_1', 'konversi_2', 'konversi_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'total_order'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code', 'satuan_code', 'material_code', 'type_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3', 'lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['keterangan'], 'string', 'max' => 128],
            [['code', 'urutan', 'item_code', 'supplier_code'], 'unique', 'targetAttribute' => ['code', 'urutan', 'item_code', 'supplier_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'konversi_1' => 'Konversi 1',
            'konversi_2' => 'Konversi 2',
            'konversi_3' => 'Konversi 3',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'satuan_ikat_code' => 'Satuan Ikat Code',
            'lembar_ikat_1' => 'Lembar Ikat 1',
            'lembar_ikat_2' => 'Lembar Ikat 2',
            'lembar_ikat_3' => 'Lembar Ikat 3',
            'lembar_ikat_um_1' => 'Lembar Ikat Um 1',
            'lembar_ikat_um_2' => 'Lembar Ikat Um 2',
            'lembar_ikat_um_3' => 'Lembar Ikat Um 3',
            'total_potong' => 'Total Potong',
            'total_warna' => 'Total Warna',
            'total_order' => 'Total Order',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }

    public function getPotongs()
    {
        return $this->hasMany(SalesOrderPotong::className(), ['code' => 'code', 'item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }

    public function getProses()
    {
        return $this->hasMany(SalesOrderProses::className(), ['code' => 'code', 'item_code' => 'item_code']);
    }

    public function getTotalOrder()
    {
        $total_order=0;
        $konversi_2 = (!empty($this->item->satuan->konversi_2)) ? $this->item->satuan->konversi_2 : 1;
        if(!empty($this->qty_order_1)){
            $total_order += $this->qty_order_1 * $this->harga_jual_1;
        }
        if(!empty($this->qty_order_2)){
            $total_order += ($this->qty_order_2 / $konversi_2) * $this->harga_jual_2;
        }
        return $total_order;
    }
}
