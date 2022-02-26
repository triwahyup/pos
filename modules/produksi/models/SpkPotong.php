<?php

namespace app\modules\produksi\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_potong".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property int $urutan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $objek
 * @property float|null $total_objek
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkPotong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_potong';
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
            [['no_spk', 'item_code', 'urutan'], 'required'],
            [['urutan', 'objek', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'total_objek'], 'number'],
            [['no_spk'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['no_spk', 'item_code', 'urutan'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'urutan']],
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
            'urutan' => 'Urutan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'objek' => 'Objek',
            'total_objek' => 'Total Objek',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
