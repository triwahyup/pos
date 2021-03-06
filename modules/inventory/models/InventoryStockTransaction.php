<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_stock_transaction".
 *
 * @property string $item_code
 * @property string $supplier_code
 * @property string|null $no_document
 * @property string|null $tgl_document
 * @property string|null $type_document
 * @property string|null $status_document
 * @property string|null $refrensi_document
 * @property float|null $qty_in
 * @property float|null $qty_out
 * @property float|null $qty_retur
 * @property float|null $onhand
 * @property float|null $onsales
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryStockTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_stock_transaction';
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
            [['tgl_document'], 'safe'],
            [['qty_in', 'qty_out', 'qty_retur', 'qty_bad', 'onhand', 'onsales'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['no_document', 'refrensi_document'], 'string', 'max' => 12],
            [['type_document'], 'string', 'max' => 32],
            [['status_document'], 'string', 'max' => 255],
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
            'supplier_code' => 'Supplier Code',
            'no_document' => 'No Document',
            'tgl_document' => 'Tgl Document',
            'type_document' => 'Type Document',
            'status_document' => 'Status Document',
            'refrensi_document' => 'Refrensi Document',
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
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getOnHand()
    {
        $model = InventoryStockTransaction::find()
            ->where(['item_code'=>$this->item_code, 'supplier_code'=>$this->supplier_code])
            ->orderBy(['updated_at'=>SORT_DESC])
            ->one();
        return $model;
    }

    public function konversi($item_code, $qty)
    {
        $item = MasterMaterial::findOne($item_code);
        $desc = '';
        $result = [];
        if(isset($item)){
            if(isset($item->satuan)){
                $konversi_2 = (!empty($item->satuan->konversi_2)) ? $item->satuan->konversi_2 : 1;
                $result[0] = floor($qty / $konversi_2);
                $sisa = $qty - ($result[0] * $konversi_2);
                $result[1] = $sisa;
                
                foreach($result as $index=>$val){
                    $desc .= $val .' '.$item->satuan['um_'.($index+1)].' / ';
                }
            }
        }
        return substr($desc, 0, -2);
    }
}
