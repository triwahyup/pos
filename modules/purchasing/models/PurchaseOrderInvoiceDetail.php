<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterMaterial;
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
            [['konversi_1', 'konversi_2', 'konversi_3', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'qty_terima_1', 'qty_terima_2', 'qty_terima_3', 'qty_selisih', 'qty_susulan', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'ppn', 'total_order', 'total_invoice'], 'safe'],
            [['no_invoice'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan_code', 'type_code', 'material_code', 'supplier_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
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
            'um_1' => 'UM 1',
            'um_2' => 'UM 2',
            'um_3' => 'UM 3',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'qty_terima_1' => 'Qty Terima 1',
            'qty_terima_2' => 'Qty Terima 2',
            'qty_terima_3' => 'Qty Terima 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
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
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function beforeSave($attribute)
    {
        $this->harga_beli_1 = str_replace(',', '', $this->harga_beli_1);
        $this->qty_terima_1 = str_replace(',', '', $this->qty_terima_1);
        return parent::beforeSave($attribute);
    }

    public function getTotalInvoice()
    {
        $total_invoice=0;
        if(!empty($this->qty_terima_1)){
            $harga_beli_1 = str_replace(',', '', $this->harga_beli_1);
            $total_invoice += $this->qty_terima_1 * $harga_beli_1;
        }
        if(!empty($this->qty_terima_2)){
            $harga_beli_2 = str_replace(',', '', $this->harga_beli_2);
            $total_invoice += $this->qty_terima_2 * $harga_beli_2;
        }
        if(!empty($this->qty_terima_3)){
            $harga_beli_3 = str_replace(',', '', $this->harga_beli_3);
            $total_invoice += $this->qty_terima_3 * $harga_beli_3;
        }

        if(!empty($this->ppn)){
            $ppn = $total_invoice / ($this->ppn*100);
            $total_invoice += $ppn;
        }
        return $total_invoice;
    }

    public function getQtySelisih($qty_order, $qty_terima)
    {
        $qty = 0;
        $konversi = 1;
        $pcs = abs($qty_terima-$qty_order);
        if($this->konversi_1 > 0){
            $qty = $total = floor($pcs / $konversi);
        }
        
        $isEmptyQty = false;
        $selisih = 0;
        if($qty_terima == 0){
            $isEmptyQty = true;
        }else{
            if(($qty_terima - $qty_order) > 0){
                $selisih = 1;
            }else{
                if(($qty_terima - $qty_order) !=0){
                    $selisih = -1;
                }
            }
        }
        return ['qty' => $qty, 'selisih' => $selisih, 'isEmptyQty' => $isEmptyQty];
    }
}
