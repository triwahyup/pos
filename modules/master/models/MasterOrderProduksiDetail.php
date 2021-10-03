<?php

namespace app\modules\master\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_order_produksi_detail".
 *
 * @property string $order_code
 * @property int $urutan
 * @property string|null $name
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property float|null $index
 * @property float|null $total_biaya
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterOrderProduksiDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_order_produksi_detail';
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
            [['panjang', 'lebar', 'index', 'total_biaya'], 'number'],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
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
            'name' => 'Name',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'index' => 'Index',
            'total_biaya' => 'Total Biaya',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
