<?php

namespace app\modules\produksi\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_detail_proses".
 *
 * @property string $no_spk
 * @property int $urutan
 * @property string $order_code
 * @property string $item_code
 * @property int $type_proses
 * @property float|null $qty_proses
 * @property string|null $mesin_code
 * @property string|null $mesin_type_code
 * @property int|null $status_proses
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkDetailProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_detail_proses';
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
            [['no_spk', 'urutan', 'order_code', 'item_code', 'type_proses'], 'required'],
            [['urutan', 'type_proses', 'status_proses', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_proses'], 'number'],
            [['no_spk'], 'string', 'max' => 12],
            [['order_code', 'mesin_code', 'mesin_type_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['no_spk', 'urutan', 'order_code', 'item_code', 'type_proses'], 'unique', 'targetAttribute' => ['no_spk', 'urutan', 'order_code', 'item_code', 'type_proses']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_spk' => 'No Spk',
            'urutan' => 'Urutan',
            'order_code' => 'Order Code',
            'item_code' => 'Item Code',
            'type_proses' => 'Type Proses',
            'qty_proses' => 'Qty Proses',
            'mesin_code' => 'Mesin Code',
            'mesin_type_code' => 'Mesin Type Code',
            'status_proses' => 'Status Proses',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
