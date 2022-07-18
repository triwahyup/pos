<?php

namespace app\modules\sales\models;

use Yii;

/**
 * This is the model class for table "sales_invoice".
 *
 * @property string $no_invoice
 * @property string|null $tgl_invoice
 * @property float|null $ppn
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
class SalesInvoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_invoice'], 'required'],
            [['tgl_invoice'], 'safe'],
            [['ppn', 'total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_ppn', 'grand_total', 'new_total_order_material', 'new_total_order_bahan', 'new_total_biaya_produksi', 'new_total_ppn', 'new_grand_total'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['no_invoice'], 'string', 'max' => 12],
            [['keterangan'], 'string', 'max' => 128],
            [['no_invoice'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_invoice' => 'No Invoice',
            'tgl_invoice' => 'Tgl Invoice',
            'ppn' => 'Ppn',
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
