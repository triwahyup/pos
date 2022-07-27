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
            [['total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_biaya_lain', 'total_ppn', 'grand_total', 'new_total_order_material', 'new_total_order_bahan', 'new_total_biaya_produksi', 'new_total_ppn', 'new_grand_total'], 'number'],
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

    public function itemsMaterial($no_invoice, $type_invoice)
    {
        $model = SalesInvoiceItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where('no_invoice =:no_invoice and type_invoice =:type_invoice and value =:value', [
                ':no_invoice'=>$no_invoice, ':type_invoice'=>$type_invoice, ':value' => \Yii::$app->params['TYPE_KERTAS']])
            ->all();
        return $model;
    }

    public function itemsNonMaterial($no_invoice, $type_invoice)
    {
        $model = SalesInvoiceItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where('no_invoice =:no_invoice and type_invoice =:type_invoice and value <> :value', [
                ':no_invoice'=>$no_invoice, ':type_invoice'=>$type_invoice, ':value' => \Yii::$app->params['TYPE_KERTAS']])
            ->all();
        return $model;
    }

    public function itemsProses($no_invoice, $type_invoice)
    {
        $model = SalesInvoiceItem::find()
            ->where('no_invoice =:no_invoice and type_invoice =:type_invoice and proses_code is not null', [
                ':no_invoice' => $no_invoice, ':type_invoice' => $type_invoice])
            ->all();
        return $model;
    }

    public function itemsLain2($no_invoice)
    {
        $model = SalesInvoiceItem::find()
            ->where('no_invoice =:no_invoice and type_invoice = 3', [':no_invoice'=>$no_invoice])
            ->all();
        return $model;
    }

    public function newTotalOrder($param, $proses_code=null)
    {
        $total_order_material = 0;
        $total_order_bahan = 0;
        $total_biaya_produksi = 0;
        $total_biaya_lain = 0;
        $grand_total = 0;
        if(!empty($proses_code)){
            foreach($param->itemsProses($param->no_invoice, $param->type_invoice) as $val){
                $total_biaya_produksi += $val->new_total_order;
            }
            $param->new_total_biaya_produksi = $total_biaya_produksi;
            $grand_total += $total_biaya_produksi;
        }else{
            foreach($param->itemsMaterial($param->no_invoice, $param->type_invoice) as $val){
                $total_order_material += $val->new_total_order;
            }
            $param->new_total_order_material = $total_order_material;
            $grand_total += $total_order_material;
            foreach($param->itemsNonMaterial($param->no_invoice, $param->type_invoice) as $val){
                $total_order_bahan += $val->new_total_order;
            }
            $param->new_total_order_bahan = $total_order_bahan;
            $grand_total += $total_order_bahan;
            foreach($param->itemsLain2($param->no_invoice) as $val){
                $total_biaya_lain += $val->total_biaya_lain;
            }
            $param->total_biaya_lain = $total_biaya_lain;
            $grand_total += $total_biaya_lain;
        }
        $param->new_grand_total = $grand_total;
        return true;
    }
}
