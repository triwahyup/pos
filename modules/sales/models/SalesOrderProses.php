<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterBiayaProduksi;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_proses".
 *
 * @property string $code
 * @property string $item_code
 * @property string $biaya_code
 * @property int|null $type 1: Cetak; 2: Potong;
 * @property float|null $index
 * @property float|null $harga
 * @property float|null $total_biaya
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_proses';
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
            [['code', 'item_code', 'biaya_code'], 'required'],
            [['type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['index', 'harga', 'total_biaya'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['biaya_code'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['code', 'item_code', 'biaya_code'], 'unique', 'targetAttribute' => ['code', 'item_code', 'biaya_code']],
            [['status'], 'default', 'value' => 1],
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
            'biaya_code' => 'Biaya Code',
            'type' => 'Type',
            'index' => 'Index',
            'harga' => 'Harga',
            'total_biaya' => 'Total Biaya',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getBiayaProduksi()
    {
        return $this->hasOne(MasterBiayaProduksi::className(), ['code' => 'biaya_code']);
    }
}
