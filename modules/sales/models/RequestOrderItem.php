<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "request_order_item".
 *
 * @property string $no_request
 * @property int $urutan
 * @property string $item_code
 * @property string $supplier_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $qty_up
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
 * @property float|null $total_order
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class RequestOrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_order_item';
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
            [['no_request', 'urutan', 'item_code', 'supplier_code'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'qty_up', 'konversi_1', 'konversi_2', 'konversi_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'total_order'], 'number'],
            [['no_request'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code', 'satuan_code', 'material_code', 'type_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['keterangan'], 'string', 'max' => 128],
            [['no_request', 'urutan', 'item_code', 'supplier_code'], 'unique', 'targetAttribute' => ['no_request', 'urutan', 'item_code', 'supplier_code']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_request' => 'No Request',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'qty_up' => 'Qty Up',
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
            'total_order' => 'Total Order',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }
}