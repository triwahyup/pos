<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_order_detail".
 *
 * @property string $no_po
 * @property int $urutan
 * @property string|null $item_code
 * @property string|null $name
 * @property float|null $qty_order
 * @property float|null $harga_beli
 * @property float|null $harga_jual
 * @property float|null $ppn
 * @property float|null $total_order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order_detail';
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
            [['no_po', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_order', 'harga_beli', 'harga_jual', 'ppn', 'total_order'], 'number'],
            [['no_po'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan'], 'string', 'max' => 5],
            [['no_po', 'urutan'], 'unique', 'targetAttribute' => ['no_po', 'urutan']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_po' => 'No Po',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'name' => 'Name',
            'satuan' => 'Satuan',
            'qty_order' => 'Qty',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'ppn' => 'Ppn',
            'total_order' => 'Total',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }
}
