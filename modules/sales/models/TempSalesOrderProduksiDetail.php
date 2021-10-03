<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;

/**
 * This is the model class for table "temp_sales_order_produksi_detail".
 *
 * @property int $id
 * @property string|null $no_so
 * @property string|null $order_code
 * @property int|null $urutan
 * @property string|null $name
 * @property string|null $item_code
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property float|null $index
 * @property float|null $total_biaya
 * @property int|null $user_id
 */
class TempSalesOrderProduksiDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_produksi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'type', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'index', 'total_biaya'], 'number'],
            [['no_so'], 'string', 'max' => 12],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['item_code'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_so' => 'No So',
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'item_code' => 'Item Code',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'index' => 'Index',
            'total_biaya' => 'Total Biaya',
            'user_id' => 'User ID',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }
}
