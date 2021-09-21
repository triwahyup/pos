<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_order_detail".
 *
 * @property string $order_code
 * @property int $urutan
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
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_code', 'urutan'], 'required'],
            [['urutan', 'potong', 'objek', 'mesin', 'jumlah_warna', 'lembar_ikat', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'harga_jual', 'harga_cetak'], 'number'],
            [['order_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['satuan'], 'string', 'max' => 5],
            [['order_code', 'urutan'], 'unique', 'targetAttribute' => ['order_code', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'satuan' => 'Satuan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'potong' => 'Potong',
            'objek' => 'Objek',
            'mesin' => 'Mesin',
            'jumlah_warna' => 'Jumlah Warna',
            'lembar_ikat' => 'Lembar Ikat',
            'harga_jual' => 'Harga Jual',
            'harga_cetak' => 'Harga Cetak',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
