<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_order_invoice_detail".
 *
 * @property string $no_invoice
 * @property int $urutan
 * @property string|null $item_code
 * @property string|null $name
 * @property string|null $satuan
 * @property float|null $qty_order
 * @property float|null $qty_terima
 * @property float|null $harga_beli
 * @property float|null $harga_jual
 * @property float|null $ppn
 * @property float|null $total_order
 * @property float|null $total_invoice
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseOrderInvoiceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order_invoice_detail';
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
            [['no_invoice', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_order', 'qty_terima', 'harga_beli', 'harga_jual', 'ppn', 'total_order', 'total_invoice'], 'number'],
            [['no_invoice'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan'], 'string', 'max' => 3],
            [['no_invoice', 'urutan'], 'unique', 'targetAttribute' => ['no_invoice', 'urutan']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_invoice' => 'No Invoice',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'name' => 'Name',
            'satuan' => 'Satuan',
            'qty_order' => 'Qty Order',
            'qty_terima' => 'Qty Terima',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'ppn' => 'Ppn',
            'total_order' => 'Total Order',
            'total_invoice' => 'Total Invoice',
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
