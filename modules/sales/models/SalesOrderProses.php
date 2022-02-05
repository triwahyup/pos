<?php

namespace app\modules\sales\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_proses".
 *
 * @property string $code
 * @property string $item_code
 * @property int $detail_id
 * @property string $biaya_code
 * @property int|null $type 1: Cetak; 2: Potong;
 * @property float|null $index
 * @property float|null $harga
 * @property float|null $total_biaya
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
            [['code', 'item_code', 'detail_id', 'biaya_code'], 'required'],
            [['detail_id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['index', 'harga', 'total_biaya'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['biaya_code'], 'string', 'max' => 3],
            [['code', 'item_code', 'detail_id', 'biaya_code'], 'unique', 'targetAttribute' => ['code', 'item_code', 'detail_id', 'biaya_code']],
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
            'detail_id' => 'Detail ID',
            'biaya_code' => 'Biaya Code',
            'type' => 'Type',
            'index' => 'Index',
            'harga' => 'Harga',
            'total_biaya' => 'Total Biaya',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
