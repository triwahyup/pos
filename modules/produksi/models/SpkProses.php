<?php

namespace app\modules\produksi\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_proses".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property string $biaya_code
 * @property int|null $type 1: Cetak; 2: Potong;
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_proses';
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
            [['no_spk', 'item_code', 'biaya_code'], 'required'],
            [['type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['no_spk'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['biaya_code'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'item_code', 'biaya_code'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'biaya_code']],
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
            'item_code' => 'Item Code',
            'biaya_code' => 'Biaya Code',
            'type' => 'Type',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
