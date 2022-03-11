<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMaterialItemPricelist;

/**
 * This is the model class for table "temp_request_order_item".
 *
 * @property string $no_request
 * @property int $urutan
 * @property string $no_spk
 * @property string $no_so
 * @property string|null $item_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $group_supplier_code
 * @property string|null $group_material_code
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $total_order
 * @property int|null $user_id
 */
class TempRequestOrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_request_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_spk'], 'required'],
            [['urutan', 'user_id'], 'integer'],
            [['harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'total_order'], 'number'],
            [['item_name'], 'string', 'max' => 128],
            [['no_request', 'no_spk', 'no_so'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['satuan_code', 'material_code', 'type_code', 'group_supplier_code', 'group_material_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
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
            'no_spk' => 'No Spk',
            'no_so' => 'No So',
            'item_code' => 'Item Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'group_supplier_code' => 'Group Supplier Code',
            'group_material_code' => 'Group Material Code',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'total_order' => 'Total Order',
            'user_id' => 'User ID',
        ];
    }

    public function getCount()
    {
        return TempRequestOrderItem::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempRequestOrderItem::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getItemPricelist()
    {
        return $this->hasOne(MasterMaterialItemPricelist::className(), ['item_code' => 'item_code']);
    }

    public function beforeSave($attribute)
    {
        $this->qty_order_1 = str_replace(',', '', $this->qty_order_1);
        $this->qty_order_2 = str_replace(',', '', $this->qty_order_2);
        $this->qty_order_3 = str_replace(',', '', $this->qty_order_3);
        return parent::beforeSave($attribute);
    }

    public function getTotalOrder()
    {
        $total_order=0;
        if(!empty($this->qty_order_1)){
            $total_order += $this->qty_order_1 * $this->harga_jual_1;
        }
        if(!empty($this->qty_order_2)){
            $total_order += ($this->qty_order_2 / 1000) * $this->harga_jual_2;
        }
        return $total_order;
    }
}