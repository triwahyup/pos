<?php

namespace app\modules\master\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\TempMasterOrderDetailProduksi;

/**
 * This is the model class for table "temp_master_order_detail".
 *
 * @property int $id
 * @property string|null $order_code
 * @property int|null $urutan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_warna
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
            [['panjang', 'lebar', 'harga_cetak', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3'], 'safe'],
            [['order_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['jumlah_cetak', 'jumlah_objek'], 'number'],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['keterangan_cetak', 'keterangan_potong', 'keterangan_pond'], 'string', 'max' => 128],
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
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Potong',
            'total_objek' => 'Objek',
            'total_warna' => 'Jumlah Warna',
            'harga_cetak' => 'Harga Cetak',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->harga_cetak = str_replace(',', '', $this->harga_cetak);
        $this->total_warna = str_replace(',', '', $this->total_warna);
        $this->lembar_ikat_1 = str_replace(',', '', $this->lembar_ikat_1);
        $this->lembar_ikat_2 = str_replace(',', '', $this->lembar_ikat_2);
        $this->lembar_ikat_3 = str_replace(',', '', $this->lembar_ikat_3);
        $this->total_potong = str_replace(',', '', $this->total_potong);
        $this->total_objek = str_replace(',', '', $this->total_objek);
        return parent::beforeSave($attribute);
    }

    

    public function getCount()
    {
        return TempMasterOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempMasterOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function jumlahProses($item_code, $qty_order_1, $qty_order_2)
    {
        $inventoryStock = InventoryStockItem::findOne(['item_code'=>$item_code]);
        $konversi = $inventoryStock->satuanTerkecil($item_code, [
            0 => $qty_order_1,
            1 => $qty_order_2
        ]);
        $this->jumlah_cetak = $konversi * $this->total_potong;
        $this->jumlah_objek = $this->jumlah_cetak * $this->total_objek;
        return true;
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(TempMasterOrderDetailProduksi::className(), ['order_code' => 'order_code']);
    }
}
