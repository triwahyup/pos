<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;

/**
 * This is the model class for table "temp_inventory_opname_detail".
 *
 * @property int $id
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
 * @property int|null $user_id
 */
class TempInventoryOpnameDetail extends \yii\db\ActiveRecord
{
    public $um_stock_1;
    public $um_stock_2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_inventory_opname_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_code'], 'required'],
            [['urutan', 'balance', 'user_id'], 'integer'],
            [['qty_1', 'qty_2', 'qty_3', 'qty_stock_1', 'qty_stock_2', 'qty_stock_3', 'selisih', 'konversi_1', 'konversi_2', 'konversi_3'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code', 'satuan_code', 'material_code', 'type_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['item_name', 'keterangan'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'item_name' => 'Item Name',
            'supplier_code' => 'Supplier',
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
            'user_id' => 'User ID',
        ];
    }

    public function getCount()
    {
        return TempInventoryOpnameDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempInventoryOpnameDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
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
