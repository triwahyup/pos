<?php

namespace app\modules\master\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_order_detail_produksi".
 *
 * @property string $order_code
 * @property int $urutan
 * @property string|null $name
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property float|null $harga
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterOrderDetailProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_order_detail_produksi';
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
            [['order_code', 'urutan'], 'required'],
            [['urutan', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'harga'], 'number'],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['order_code', 'urutan', 'item_code'], 'unique', 'targetAttribute' => ['order_code', 'urutan', 'item_code']],
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
            'name' => 'Name',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'harga' => 'Harga',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
