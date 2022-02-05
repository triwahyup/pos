<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;

/**
 * This is the model class for table "temp_sales_order_detail".
 *
 * @property int $id
 * @property string $code
 * @property int $urutan
 * @property string|null $satuan_ikat_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_potong
 * @property int|null $total_objek
 * @property int|null $total_warna
 * @property int|null $lembar_ikat_1
 * @property int|null $lembar_ikat_2
 * @property int|null $lembar_ikat_3
 * @property string|null $lembar_ikat_um_1
 * @property string|null $lembar_ikat_um_2
 * @property string|null $lembar_ikat_um_3
 * @property float|null $jumlah_cetak
 * @property float|null $jumlah_objek
 * @property string|null $keterangan_cetak
 * @property string|null $keterangan_potong
 * @property string|null $keterangan_pond
 * @property int|null $user_id
 */
class TempSalesOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'jumlah_cetak', 'jumlah_objek'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['satuan_ikat_code'], 'string', 'max' => 3],
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
            'code' => 'Code',
            'urutan' => 'Urutan',
            'satuan_ikat_code' => 'Satuan Ikat Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Total Potong',
            'total_objek' => 'Total Objek',
            'total_warna' => 'Total Warna',
            'lembar_ikat_1' => 'Lembar Ikat 1',
            'lembar_ikat_2' => 'Lembar Ikat 2',
            'lembar_ikat_3' => 'Lembar Ikat 3',
            'lembar_ikat_um_1' => 'Lembar Ikat Um 1',
            'lembar_ikat_um_2' => 'Lembar Ikat Um 2',
            'lembar_ikat_um_3' => 'Lembar Ikat Um 3',
            'jumlah_cetak' => 'Jumlah Cetak',
            'jumlah_objek' => 'Jumlah Objek',
            'keterangan_cetak' => 'Keterangan Cetak',
            'keterangan_potong' => 'Keterangan Potong',
            'keterangan_pond' => 'Keterangan Pond',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->total_warna = str_replace(',', '', $this->total_warna);
        $this->lembar_ikat_1 = str_replace(',', '', $this->lembar_ikat_1);
        $this->lembar_ikat_2 = str_replace(',', '', $this->lembar_ikat_2);
        $this->lembar_ikat_3 = str_replace(',', '', $this->lembar_ikat_3);
        $this->total_potong = str_replace(',', '', $this->total_potong);
        $this->total_objek = str_replace(',', '', $this->total_objek);
        return parent::beforeSave($attribute);
    }

    public function getTemps()
    {
        return TempSalesOrderDetail::find()->where(['code'=>$this->code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempSalesOrderDetail::find()->where(['item_code'=>$this->item_code, 'user_id'=> \Yii::$app->user->id])->count();
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
}
