<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialPricelist;
use app\modules\master\models\MasterSatuan;

/**
 * This is the model class for table "temp_purchase_order_detail".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $no_po
 * @property int|null $urutan
 * @property string|null $item_code
 * @property string|null $name
 * @property string|null $satuan
 * @property float|null $qty_order
 * @property float|null $ppn
 * @property float|null $total_order
 */
class TempPurchaseOrderDetail extends \yii\db\ActiveRecord
{
    public $item_name;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_purchase_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'urutan'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'ppn', 'total_order', 'konversi_1', 'konversi_2', 'konversi_3'], 'safe'],
            [['no_po'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan_code', 'type_code', 'material_code', 'supplier_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
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
            'ppn' => 'Ppn',
            'total_order' => 'Total',
            'satuan' => 'Satuan',
        ];
    }

    public function getCount()
    {
        return TempPurchaseOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempPurchaseOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public $status_active=1;
    public function getPriceListActive()
    {
        return $this->hasOne(MasterMaterialPricelist::className(), ['item_code' => 'item_code', 'supplier_code' => 'supplier_code', 'status_active' => 'status_active']);
    }

    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public function totalBeli($temp)
    {
        $total_order=0;
        $qty_order_1 = str_replace(',', '', $temp->qty_order_1);
        if(!empty($qty_order_1)){
            $harga_beli_1 = str_replace(',', '', $temp->harga_beli_1);
            $total_order += $qty_order_1 * $harga_beli_1;
        }
        $qty_order_2 = str_replace(',', '', $temp->qty_order_2);
        if(!empty($qty_order_2)){
            $harga_beli_2 = str_replace(',', '', $temp->harga_beli_2);
            $total_order += $qty_order_2 * $harga_beli_2;
        }
        if(!empty($temp->ppn)){
            $ppn = $total_order / ($temp->ppn*100);
            $total_order += $ppn;
        }
        return $total_order;
    }
}
