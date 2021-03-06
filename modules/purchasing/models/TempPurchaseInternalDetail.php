<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterBarangPricelist;
use app\modules\master\models\MasterSatuan;


/**
 * This is the model class for table "temp_purchase_internal_detail".
 *
 * @property int $id
 * @property string $no_po
 * @property int $urutan
 * @property string|null $item_name
 * @property float|null $qty
 * @property float|null $harga_beli
 * @property float|null $total_order
 * @property int|null $user_id
 */
class TempPurchaseInternalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_purchase_internal_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'user_id'], 'integer'],
            [['ppn', 'qty_order_1', 'qty_order_2', 'harga_beli_1', 'harga_beli_2', 'total_order'], 'safe'],
            [['supplier_code', 'satuan_code'], 'string', 'max' => 3],
            [['um_1', 'um_2'], 'string', 'max' => 5],
            [['barang_code'], 'string', 'max' => 7],
            [['no_po'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_po' => 'No Pi',
            'urutan' => 'Urutan',
            'item_name' => 'Item Name',
            'qty_order_1' => 'Qty',
            'harga_beli_1' => 'Harga Beli',
            'total_order' => 'Total Order',
            'user_id' => 'User ID',
        ];
    }

    public function getBarang()
    {
        return $this->hasOne(MasterBarang::className(), ['code' => 'barang_code']);
    }

    public $status_active=1;
    public function getPriceListActive()
    {
        return $this->hasOne(MasterBarangPricelist::className(), ['barang_code' => 'barang_code', 'supplier_code' => 'supplier_code', 'status_active' => 'status_active']);
    }
    
    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public function getCount()
    {
        return TempPurchaseInternalDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempPurchaseInternalDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
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
