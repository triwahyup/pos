<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "inventory_stock_bast".
 *
 * @property string $code
 * @property string $supplier_code
 * @property string|null $no_bast
 * @property string|null $tgl_bast
 * @property string|null $type_bast
 * @property string|null $status_bast
 * @property float|null $bast_in
 * @property float|null $bast_out
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'supplier_code', 'created_at'], 'required'],
            [['tgl_bast'], 'safe'],
            [['bast_in', 'bast_out', 'stock'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['no_bast'], 'string', 'max' => 12],
            [['type_bast'], 'string', 'max' => 32],
            [['status_bast'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'supplier_code' => 'Supplier Code',
            'no_bast' => 'No Bast',
            'tgl_bast' => 'Tgl Bast',
            'type_bast' => 'Type Bast',
            'status_bast' => 'Status Bast',
            'bast_in' => 'Bast In',
            'bast_out' => 'Bast Out',
            'stock' => 'Stock',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
