<?php

namespace app\modules\sales\models;

use Yii;

/**
 * This is the model class for table "sales_order_detail_produksi".
 *
 * @property string $no_so
 * @property int $urutan
 * @property string $order_code
 * @property string|null $name
 * @property string|null $item_code
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderDetailProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_detail_produksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_so', 'detail_urutan', 'urutan', 'order_code'], 'required'],
            [['detail_urutan', 'urutan', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'harga'], 'number'],
            [['no_so'], 'string', 'max' => 12],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['item_code'], 'string', 'max' => 7],
            [['no_so', 'detail_urutan', 'urutan'], 'unique', 'targetAttribute' => ['no_so', 'detail_urutan', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_so' => 'No So',
            'urutan' => 'Urutan',
            'order_code' => 'Order Code',
            'name' => 'Name',
            'item_code' => 'Item Code',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
