<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterBarang;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_internal_invoice_detail".
 *
 * @property string $no_invoice
 * @property int $urutan
 * @property string|null $barang_code
 * @property string|null $name
 * @property string|null $supplier_code
 * @property string|null $satuan_code
 * @property string|null $um
 * @property float|null $qty_order
 * @property float|null $qty_terima
 * @property float|null $qty_selisih
 * @property float|null $qty_susulan
 * @property float|null $harga_beli
 * @property float|null $ppn
 * @property float|null $total_order
 * @property float|null $total_invoice
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseInternalInvoiceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_internal_invoice_detail';
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
            [['qty_order', 'qty_terima', 'qty_selisih', 'qty_susulan', 'harga_beli', 'ppn', 'total_order', 'total_invoice'], 'number'],
            [['no_invoice'], 'string', 'max' => 12],
            [['barang_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['supplier_code', 'satuan_code'], 'string', 'max' => 3],
            [['um'], 'string', 'max' => 5],
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
            'barang_code' => 'Barang Code',
            'name' => 'Name',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'um' => 'Um',
            'qty_order' => 'Qty Order',
            'qty_terima' => 'Qty Terima',
            'qty_selisih' => 'Qty Selisih',
            'qty_susulan' => 'Qty Susulan',
            'harga_beli' => 'Harga Beli',
            'ppn' => 'Ppn',
            'total_order' => 'Total Order',
            'total_invoice' => 'Total Invoice',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getBarang()
    {
        return $this->hasOne(MasterBarang::className(), ['code' => 'barang_code']);
    }

    public function beforeSave($attribute)
    {
        $this->harga_beli = str_replace(',', '', $this->harga_beli);
        $this->qty_terima = str_replace(',', '', $this->qty_terima);
        return parent::beforeSave($attribute);
    }

    public function getTotalInvoice()
    {
        $total_invoice=0;
        if(!empty($this->qty_terima)){
            $harga_beli = str_replace(',', '', $this->harga_beli);
            $total_invoice += $this->qty_terima * $harga_beli;
        }
        
        if(!empty($this->ppn)){
            $ppn = $total_invoice / ($this->ppn*100);
            $total_invoice += $ppn;
        }
        return $total_invoice;
    }

    // public function getQtySelisih($qty_order, $qty_terima)
    // {
    //     $qty = 0;
    //     $konversi = 1;
    //     $pcs = abs($qty_terima-$qty_order);
    //     if($this->konversi_1 > 0){
    //         $qty = $total = floor($pcs / $konversi);
    //     }
        
    //     $isEmptyQty = false;
    //     $selisih = 0;
    //     if($qty_terima == 0){
    //         $isEmptyQty = true;
    //     }else{
    //         if(($qty_terima - $qty_order) > 0){
    //             $selisih = 1;
    //         }else{
    //             if(($qty_terima - $qty_order) !=0){
    //                 $selisih = -1;
    //             }
    //         }
    //     }
    //     return ['qty' => $qty, 'selisih' => $selisih, 'isEmptyQty' => $isEmptyQty];
    // }
}
