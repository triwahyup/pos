<?php

namespace app\modules\sales\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_invoice_detail".
 *
 * @property string $no_invoice
 * @property string|null $no_sales
 * @property string|null $no_request
 * @property float|null $total_order_material
 * @property float|null $total_order_bahan
 * @property float|null $total_biaya_produksi
 * @property float|null $total_ppn
 * @property float|null $grand_total
 * @property float|null $new_total_order_material
 * @property float|null $new_total_order_bahan
 * @property float|null $new_total_biaya_produksi
 * @property float|null $new_total_ppn
 * @property float|null $new_grand_total
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesInvoiceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_invoice_detail';
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
            [['no_invoice'], 'required'],
            [['total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_ppn', 'grand_total', 'new_total_order_material', 'new_total_order_bahan', 'new_total_biaya_produksi', 'new_total_ppn', 'new_grand_total'], 'number'],
            [['urutan', 'type_invoice', 'status', 'created_at', 'updated_at'], 'integer'],
            [['no_sales', 'no_request'], 'string', 'max' => 12],
            [['no_invoice'], 'string', 'max' => 15],
            [['keterangan'], 'string', 'max' => 128],
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
            'no_sales' => 'No Sales',
            'no_request' => 'No Request',
            'total_order_material' => 'Total Order Material',
            'total_order_bahan' => 'Total Order Bahan',
            'total_biaya_produksi' => 'Total Biaya Produksi',
            'total_ppn' => 'Total Ppn',
            'grand_total' => 'Grand Total',
            'new_total_order_material' => 'New Total Order Material',
            'new_total_order_bahan' => 'New Total Order Bahan',
            'new_total_biaya_produksi' => 'New Total Biaya Produksi',
            'new_total_ppn' => 'New Total Ppn',
            'new_grand_total' => 'New Grand Total',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
