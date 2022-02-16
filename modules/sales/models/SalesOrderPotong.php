<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\sales\models\SalesOrderProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_potong".
 *
 * @property string $code
 * @property string $item_code
 * @property int $urutan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_objek
 * @property float|null $jumlah_objek
 * @property string|null $keterangan_cetak
 * @property string|null $keterangan_potong
 * @property string|null $keterangan_pond
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderPotong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_potong';
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
            [['code', 'item_code', 'urutan'], 'required'],
            [['urutan', 'total_objek', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'jumlah_objek'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
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
            'item_code' => 'Item Code',
            'urutan' => 'Urutan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_objek' => 'Total Objek',
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
        return $this->hasMany(SalesOrderProses::className(), ['code' => 'code', 'item_code' => 'item_code', 'potong_id' => 'urutan']);
    }
}
