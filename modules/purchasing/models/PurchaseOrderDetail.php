<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_order_detail".
 *
 * @property string $no_po
 * @property int $urutan
 * @property string|null $item_code
 * @property string|null $name
 * @property float|null $qty_order
 * @property float|null $harga_beli
 * @property float|null $harga_jual
 * @property float|null $ppn
 * @property float|null $total_order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order_detail';
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
            [['no_po', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'ppn', 'total_order'], 'number'],
            [['no_po'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan_code', 'type_code', 'material_code', 'group_material_code', 'group_supplier_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['no_po', 'urutan'], 'unique', 'targetAttribute' => ['no_po', 'urutan']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_po' => 'No Po',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'name' => 'Name',
            'um_1' => 'UM 1',
            'um_2' => 'UM 2',
            'um_3' => 'UM 3',
            'qty_order_1' => 'Qty 1',
            'qty_order_2' => 'Qty 2',
            'qty_order_3' => 'Qty 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'ppn' => 'Ppn',
            'satuan' => 'Satuan',
            'total_order' => 'Total',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }
}