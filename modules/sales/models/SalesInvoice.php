<?php

namespace app\modules\sales\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
    public $no_so;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_invoice';
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
            [['tgl_invoice'], 'safe'],
            [['ppn', 'total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_biaya_lain', 'total_ppn', 'grand_total', 'new_total_order_material', 'new_total_order_bahan', 'new_total_biaya_produksi', 'new_total_ppn', 'new_grand_total'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['no_so'], 'string', 'max' => 12],
            [['no_invoice'], 'string', 'max' => 15],
            [['keterangan'], 'string', 'max' => 128],
            [['no_invoice'], 'unique'],
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

    public function generateCode()
    {
        $model = SalesInvoice::find()->count();
        $total=0;
        if($model > 0){
            $model = SalesInvoice::find()->orderBy(['no_invoice'=>SORT_DESC])->one();
            $total = (int)substr($model->no_invoice, -4);
        }
        return 'INV'.(string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getDetails()
    {
        return $this->hasMany(SalesInvoiceDetail::className(), ['no_invoice' => 'no_invoice']);
    }

    public function getItems()
    {
        return $this->hasMany(SalesInvoiceItem::className(), ['no_invoice' => 'no_invoice']);
    }

    public $type_so=1;
    public function getItemsSo()
    {
        return $this->hasMany(SalesInvoiceItem::className(), ['no_invoice' => 'no_invoice', 'type_invoice' => 'type_so']);
    }

    public $type_ro=2;
    public function getItemsRo()
    {
        return $this->hasMany(SalesInvoiceItem::className(), ['no_invoice' => 'no_invoice', 'type_invoice' => 'type_ro']);
    }
    
    public $type_lain=3;
    public function getItemsLain()
    {
        return $this->hasMany(SalesInvoiceItem::className(), ['no_invoice' => 'no_invoice', 'type_invoice' => 'type_lain']);
    }

    public function newTotalOrder($param)
    {
        $total_order_material = 0;
        $total_order_bahan = 0;
        $total_biaya_produksi = 0;
        $total_biaya_lain = 0;
        $grand_total = 0;
        foreach($param->details as $val){
            $total_order_material += $val->new_total_order_material;
            $total_order_bahan += $val->new_total_order_bahan;
            $total_biaya_produksi += $val->new_total_biaya_produksi;
            $total_biaya_lain += $val->total_biaya_lain;
        }
        $param->new_total_order_material = $total_order_material;
        $grand_total += $total_order_material;
        $param->new_total_order_bahan = $total_order_bahan;
        $grand_total += $total_order_bahan;
        $param->new_total_biaya_produksi = $total_biaya_produksi;
        $grand_total += $total_biaya_produksi;
        $param->total_biaya_lain = $total_biaya_lain;
        $grand_total += $total_biaya_lain;
        $param->new_grand_total = $grand_total + $param->new_total_ppn;
        return true;
    }
}
