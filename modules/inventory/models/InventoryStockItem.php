<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_stock_item".
 *
 * @property string $item_code
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
            [['onhand', 'onsales'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['item_code', 'supplier_code'], 'unique', 'targetAttribute' => ['item_code', 'supplier_code']],
            [['onhand', 'onsales'], 'default', 'value' => 0],
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
            'supplier_code' => 'Supplier',
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

    public function satuanTerkecil($item_code, $qty)
    {
        $total_material = 0;
        $item = MasterMaterial::findOne($item_code);
        if(isset($item)){
            if(isset($item->satuan)){
                $konversi_2 = (!empty($item->satuan->konversi_2)) ? $item->satuan->konversi_2 : 1;
                $total_material = ($qty[0] * $item->satuan->konversi_2) + $qty[1];
            }
        }
        return $total_material;
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

    public function nKonversi($item_code, $qty)
    {
        $item = MasterMaterial::findOne($item_code);
        $result = [];
        if(isset($item)){
            // KERTAS
            if($item->type_code == '007'){
                $result[0] = floor($qty / 500);
                $sisa = $qty - ($result[0] * 500);
                $result[1] = $sisa;
            }
            // BAHAN PEMBANTU
            else if($item->type_code == '010'){
                // TINTA
                if($item->material_code == '016'){
                    $result[0] = floor($qty / 1000);
                    $sisa = $qty - ($result[0] * 1000);
                    $result[1] = $sisa;
                }
                // BOX, TAS PLASTIK, SINGLE FACE
                else if($item->material_code == '017' || $item->material_code == '018' || $item->material_code == '019'){
                    $result[0] = $qty;
                }
                // LAIN2
                else{
                    if($item->satuan->um_1 == 'KG'){
                        $result[0] = floor($qty / 1000);
                        $sisa = $qty - ($result[0] * 1000);
                        $result[1] = $sisa;
                    }else{
                        $result[0] = $qty;
                    }
                }
            }
        }
        return $result;
    }
}
