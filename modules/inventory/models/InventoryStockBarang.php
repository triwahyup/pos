<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterPerson;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_stock_barang".
 *
 * @property string $barang_code
 * @property string $supplier_code
 * @property float|null $stock
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryStockBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_stock_barang';
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
            [['barang_code', 'supplier_code'], 'required'],
            [['stock'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['barang_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['barang_code', 'supplier_code'], 'unique', 'targetAttribute' => ['barang_code', 'supplier_code']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_code' => 'Barang',
            'supplier_code' => 'Supplier',
            'stock' => 'Stock',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getBarang()
    {
        return $this->hasOne(MasterBarang::className(), ['code' => 'barang_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }
}