<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterOrderDetailProduksi;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_order_detail".
 *
 * @property string $order_code
 * @property int $urutan
 * @property string|null $item_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_warna
 * @property int|null $lembar_ikat
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
            [['urutan', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'harga_cetak', 'jumlah_cetak', 'jumlah_objek'], 'number'],
            [['order_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['keterangan_cetak', 'keterangan_potong', 'keterangan_pond'], 'string', 'max' => 128],
            [['order_code', 'urutan'], 'unique', 'targetAttribute' => ['order_code', 'urutan']],
            [['status'], 'default', 'value' => 1],
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
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Potong',
            'total_objek' => 'Objek',
            'total_warna' => 'Jumlah Warna',
            'lembar_ikat' => 'Lembar Ikat',
            'harga_cetak' => 'Harga Cetak',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(MasterOrderDetailProduksi::className(), ['order_code' => 'order_code', 'urutan' => 'urutan']);
    }
}
