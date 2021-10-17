<?php

namespace app\modules\master\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\TempMasterOrderDetailProduksi;

/**
 * This is the model class for table "temp_master_order_detail".
 *
 * @property int $id
 * @property string|null $order_code
 * @property int|null $urutan
 * @property string|null $item_code
 * @property string|null $satuan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $potong
 * @property int|null $objek
 * @property int|null $mesin
 * @property int|null $jumlah_warna
 * @property int|null $lembar_ikat
 * @property float|null $harga_jual
 * @property float|null $harga_cetak
 * @property int|null $user_id
 */
class TempMasterOrderDetail extends \yii\db\ActiveRecord
{
    public $item_name;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'harga_cetak', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'potong', 'objek', 'mesin', 'jumlah_warna', 'lembar_ikat', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'total_order'], 'safe'],
            [['order_code', 'satuan_code', 'type_code', 'material_code', 'group_material_code', 'group_supplier_code'], 'string', 'max' => 3],
            [['jumlah_cetak', 'jumlah_objek', 'jumlah_lem'], 'number'],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['item_code'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'item_code' => 'Material',
            'satuan_code' => 'Satuan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'potong' => 'Potong',
            'objek' => 'Objek',
            'mesin' => 'Mesin',
            'jumlah_warna' => 'Jumlah Warna',
            'lembar_ikat' => 'Lembar Ikat',
            'harga_jual' => 'Harga Jual',
            'harga_cetak' => 'Harga Cetak',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->harga_cetak = str_replace(',', '', $this->harga_cetak);
        $this->qty_order_1 = str_replace(',', '', $this->qty_order_1);
        $this->qty_order_2 = str_replace(',', '', $this->qty_order_2);
        $this->jumlah_warna = str_replace(',', '', $this->jumlah_warna);
        $this->lembar_ikat = str_replace(',', '', $this->lembar_ikat);
        $this->mesin = str_replace(',', '', $this->mesin);
        $this->potong = str_replace(',', '', $this->potong);
        $this->objek = str_replace(',', '', $this->objek);
        $this->total_order = str_replace(',', '', $this->total_order);
        return parent::beforeSave($attribute);
    }

    public function getTotalOrder()
    {
        $total_order=0;
        if(!empty($this->qty_order_1)){
            $harga_jual_1 = str_replace(',', '', $this->harga_jual_1);
            $total_order += $this->qty_order_1 * $harga_jual_1;
        }
        if(!empty($this->qty_order_2)){
            $harga_jual_2 = str_replace(',', '', $this->harga_jual_2);
            $total_order += $this->qty_order_2 * $harga_jual_2;
        }
        if(!empty($this->qty_order_3)){
            $harga_jual_3 = str_replace(',', '', $this->harga_jual_3);
            $total_order += $this->qty_order_3 * $harga_jual_3;
        }
        return $total_order;
    }

    public function getCount()
    {
        return TempMasterOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempMasterOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code']);
    }

    public function jumlahProses()
    {
        $konversi = $this->inventoryStock->satuanTerkecil($this->item_code, [
            0 => $this->qty_order_1,
            1 => $this->qty_order_2
        ]);
        $this->jumlah_cetak = $konversi * $this->potong;
        $this->jumlah_objek = $this->jumlah_cetak * $this->objek;
        $this->jumlah_lem = 0;
        return true;
    }

    public function getDetailsProduksi()
    {
        if(!empty($this->order_code)){
            return $this->hasMany(TempMasterOrderDetailProduksi::className(), ['order_code' => 'order_code', 'item_code' => 'item_code']);
        }else{
            return $this->hasMany(TempMasterOrderDetailProduksi::className(), ['item_code' => 'item_code', 'user_id'=> 'user_id']);
        }
    }
}
