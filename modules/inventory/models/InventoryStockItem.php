<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_stock_item".
 *
 * @property string $item_code
 * @property float|null $qty_in
 * @property float|null $qty_out
 * @property float|null $qty_retur
 * @property float|null $onhand
 * @property float|null $onsales
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryStockItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_stock_item';
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
            [['item_code'], 'required'],
            [['qty_in', 'qty_out', 'qty_retur', 'onhand', 'onsales'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['item_code'], 'string', 'max' => 7],
            [['item_code'], 'unique'],
            [['qty_in', 'qty_out', 'qty_retur', 'onhand', 'onsales'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_code' => 'Item Code',
            'qty_in' => 'Qty In',
            'qty_out' => 'Qty Out',
            'qty_retur' => 'Qty Retur',
            'onhand' => 'Onhand',
            'onsales' => 'Onsales',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function satuanTerkecil($item_code, $qty_terima)
    {
        $item = MasterMaterialItem::findOne($item_code);
        $total_material = 0;
        if(isset($item->typeCode)){
            $type = $item->typeCode->value;
            if($type == 'KERTAS'){
                $total_material = ($qty_terima[0] * 500) + $qty_terima[1];
            }
        }
        return $total_material;
    }

    public function konversi($item_code, $qty)
    {
        $item = MasterMaterialItem::findOne($item_code);
        $desc = '';
        $result = [];
        if(isset($item)){
            $result[0] = floor($qty / 500);
            $sisa = $qty - ($result[0] * 500);
            $result[1] = $sisa;
        }
        foreach($result as $index=>$val){
            if(!empty($val)){
                $desc .= $val .((!empty($val)) ? ' '.$item['um_'.($index+1)] : '').' / ';
            }else{
                $desc = '';
            }
        }
        return substr($desc, 0, -2);
    }
}
