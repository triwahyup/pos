<?php

namespace app\modules\produksi\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_detail_produksi".
 *
 * @property string $no_spk
 * @property int $urutan
 * @property string $order_code
 * @property string|null $name
 * @property string|null $item_code
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property float|null $total_biaya
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkDetailProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_detail_produksi';
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
            [['no_spk', 'detail_urutan', 'urutan', 'order_code'], 'required'],
            [['detail_urutan', 'urutan', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'total_biaya'], 'number'],
            [['no_spk'], 'string', 'max' => 12],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['item_code'], 'string', 'max' => 7],
            [['no_spk', 'detail_urutan', 'urutan'], 'unique', 'targetAttribute' => ['no_spk', 'detail_urutan', 'urutan']],
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
            'name' => 'Name',
            'item_code' => 'Item Code',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'total_biaya' => 'Total Biaya',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
