<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_opname_detail".
 *
 * @property string $code
 * @property int $urutan
 * @property string $item_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $qty_1
 * @property float|null $qty_2
 * @property float|null $qty_3
 * @property float|null $qty_stock_1
 * @property float|null $qty_stock_2
 * @property float|null $qty_stock_3
 * @property float|null $selisih
 * @property int|null $balance
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryOpnameDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_opname_detail';
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
            [['code', 'urutan', 'item_code'], 'required'],
            [['urutan', 'balance', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_1', 'qty_2', 'qty_3', 'qty_stock_1', 'qty_stock_2', 'qty_stock_3', 'selisih'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['satuan_code', 'material_code', 'type_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['keterangan'], 'string', 'max' => 128],
            [['code', 'urutan', 'item_code'], 'unique', 'targetAttribute' => ['code', 'urutan', 'item_code']],
            [['status'], 'default', 'value' => 1],
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
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'qty_1' => 'Qty 1',
            'qty_2' => 'Qty 2',
            'qty_3' => 'Qty 3',
            'qty_stock_1' => 'Qty Stock 1',
            'qty_stock_2' => 'Qty Stock 2',
            'qty_stock_3' => 'Qty Stock 3',
            'selisih' => 'Selisih',
            'balance' => 'Balance',
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

    public function getStatusBalance()
    {
        $message = '';
        if($this->balance == 1){
            $message = '<span class="text-label text-success">Balance</span>';
        }else if($this->balance == 2){
            $message = '<span class="text-label text-warning">Lebih</span>';
        }else{
            $message = '<span class="text-label text-danger">Minus</span>';
        }
        return $message;
    }
}