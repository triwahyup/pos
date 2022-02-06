<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\sales\models\SalesOrderProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_detail".
 *
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
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_detail';
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
            [['code', 'urutan'], 'required'],
            [['urutan', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'jumlah_cetak', 'jumlah_objek'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['satuan_ikat_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['keterangan_cetak', 'keterangan_potong', 'keterangan_pond'], 'string', 'max' => 128],
            [['code', 'item_code', 'urutan'], 'unique', 'targetAttribute' => ['code', 'item_code', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getProses()
    {
        return $this->hasMany(SalesOrderProses::className(), ['code' => 'code', 'item_code' => 'item_code', 'detail_id' => 'urutan']);
    }
}
