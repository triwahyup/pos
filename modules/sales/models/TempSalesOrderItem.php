<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterMaterialItemPricelist;
use app\modules\sales\models\TempSalesOrderPotong;

/**
 * This is the model class for table "temp_sales_order_item".
 *
 * @property int $id
 * @property string $code
 * @property int $urutan
 * @property string $item_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $group_supplier_code
 * @property string|null $group_material_code
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property int|null $total_potong
 * @property int|null $total_warna
 * @property string|null $satuan_ikat_code
 * @property int|null $lembar_ikat_1
 * @property int|null $lembar_ikat_2
 * @property int|null $lembar_ikat_3
 * @property string|null $lembar_ikat_um_1
 * @property string|null $lembar_ikat_um_2
 * @property string|null $lembar_ikat_um_3
 * @property float|null $jumlah_cetak
 * @property float|null $total_order
 * @property string|null $keterangan
 * @property int|null $user_id
 */
class TempSalesOrderItem extends \yii\db\ActiveRecord
{
    public $item_name;
    public $bahan_item_name;
    public $bahan_item_code;
    public $bahan_qty_order_1;
    public $bahan_qty_order_2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'total_potong', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'user_id'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'jumlah_cetak', 'total_order', 'bahan_qty_order_1', 'bahan_qty_order_2'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code', 'bahan_item_code'], 'string', 'max' => 7],
            [['satuan_code', 'material_code', 'type_code', 'group_supplier_code', 'group_material_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3', 'lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['keterangan'], 'string', 'max' => 128],
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
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'group_supplier_code' => 'Group Supplier Code',
            'group_material_code' => 'Group Material Code',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'total_potong' => 'Total Potong',
            'total_warna' => 'Total Warna',
            'satuan_ikat_code' => 'Satuan Ikat Code',
            'lembar_ikat_1' => 'Lembar Ikat 1',
            'lembar_ikat_2' => 'Lembar Ikat 2',
            'lembar_ikat_3' => 'Lembar Ikat 3',
            'lembar_ikat_um_1' => 'Lembar Ikat Um 1',
            'lembar_ikat_um_2' => 'Lembar Ikat Um 2',
            'lembar_ikat_um_3' => 'Lembar Ikat Um 3',
            'jumlah_cetak' => 'Jumlah Cetak',
            'total_order' => 'Total Order',
            'keterangan' => 'Keterangan',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->qty_order_1 = str_replace(',', '', $this->qty_order_1);
        $this->qty_order_2 = str_replace(',', '', $this->qty_order_2);
        $this->qty_order_3 = str_replace(',', '', $this->qty_order_3);
        $this->total_potong = str_replace(',', '', $this->total_potong);
        $this->total_warna = str_replace(',', '', $this->total_warna);
        $this->lembar_ikat_1 = str_replace(',', '', $this->lembar_ikat_1);
        $this->lembar_ikat_2 = str_replace(',', '', $this->lembar_ikat_2);
        $this->lembar_ikat_3 = str_replace(',', '', $this->lembar_ikat_3);
        return parent::beforeSave($attribute);
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getItemBahan()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'bahan_item_code']);
    }

    public function getItemPricelist()
    {
        return $this->hasOne(MasterMaterialItemPricelist::className(), ['item_code' => 'item_code']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code']);
    }

    public function getItemTemp()
    {
        return $this->hasOne(TempSalesOrderItem::className(), ['code' => 'code', 'item_code' => 'item_code']);
    }

    public function getTemps()
    {
        return TempSalesOrderItem::find()->where(['code'=>$this->code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempSalesOrderItem::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getPotongs()
    {
        return $this->hasMany(TempSalesOrderPotong::className(), ['code' => 'code', 'item_code' => 'item_code']);
    }

    public function getTotalOrder()
    {
        $total_order=0;
        if(!empty($this->qty_order_1)){
            $total_order += $this->qty_order_1 * $this->harga_jual_1;
        }
        if(!empty($this->qty_order_2)){
            $total_order += $this->qty_order_2 * $this->harga_jual_2;
        }
        if(!empty($this->qty_order_3)){
            $total_order += $this->qty_order_3 * $this->harga_jual_3;
        }
        return $total_order;
    }
}
