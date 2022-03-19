<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterPerson;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_stock_bast".
 *
 * @property string $barang_code
 * @property string $supplier_code
 * @property string|null $no_document
 * @property string|null $tgl_document
 * @property string|null $type_document
 * @property string|null $status_document
 * @property float|null $qty_in
 * @property float|null $qty_out
 * @property float|null $stock
 * @property int|null $status
 * @property int $created_at
 * @property int|null $updated_at
 */
class InventoryStockBast extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_stock_bast';
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
            [['barang_code', 'supplier_code', 'created_at'], 'required'],
            [['tgl_document'], 'safe'],
            [['qty_in', 'qty_out', 'stock'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['barang_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['no_document'], 'string', 'max' => 12],
            [['type_document'], 'string', 'max' => 32],
            [['status_document'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_code' => 'Barang Code',
            'supplier_code' => 'Supplier',
            'no_document' => 'No Document',
            'tgl_document' => 'Tgl Document',
            'type_document' => 'Type Document',
            'status_document' => 'Status Document',
            'qty_in' => 'Qty In',
            'qty_out' => 'Qty Out',
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

    public function getOnHand()
    {
        $model = InventoryStockBast::find()
            ->where(['barang_code'=>$this->barang_code, 'supplier_code'=>$this->supplier_code])
            ->orderBy(['created_at'=>SORT_DESC])
            ->one();
        return $model;
    }
}
