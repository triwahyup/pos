<?php

namespace app\modules\sales\models;

use Yii;
use app\models\DataList;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialPricelist;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_invoice_item".
 *
 * @property string $no_invoice
 * @property int $type_invoice
 * @property int $urutan
 * @property string|null $no_sales
 * @property string|null $no_request
 * @property string $item_code
 * @property string|null $proses_code
 * @property string $supplier_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $konversi_1
 * @property float|null $konversi_2
 * @property float|null $konversi_3
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property float|null $total_order
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesInvoiceItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_invoice_item';
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
            [['no_invoice', 'type_invoice', 'urutan'], 'required'],
            [['new_harga_jual_1', 'new_harga_jual_2', 'new_total_order'], 'safe'],
            [['type_invoice', 'urutan', 'status', 'created_at', 'updated_at', 'type_ongkos'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'konversi_1', 'konversi_2', 'konversi_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'total_order'], 'number'],
            [['no_invoice'], 'string', 'max' => 15],
            [['unique_code'], 'string', 'max' => 16],
            [['no_sales', 'no_request'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'supplier_code', 'satuan_code', 'material_code', 'type_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['keterangan'], 'string', 'max' => 128],
            [['no_invoice', 'type_invoice', 'urutan'], 'unique', 'targetAttribute' => ['no_invoice', 'type_invoice', 'urutan']],
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
            'type_invoice' => 'Type Invoice',
            'urutan' => 'Urutan',
            'no_sales' => 'No Sales',
            'no_request' => 'No Request',
            'item_code' => 'Item Code',
            'proses_code' => 'Proses Code',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'konversi_1' => 'Konversi 1',
            'konversi_2' => 'Konversi 2',
            'konversi_3' => 'Konversi 3',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'total_order' => 'Total Order',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->harga_jual_1 = str_replace(',', '', $this->harga_jual_1);
        return parent::beforeSave($attribute);
    }

    public function getCount()
    {
        return SalesInvoiceItem::find()->where(['no_invoice'=> $this->no_invoice])->count();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public $status_active=1;
    public function getItemPricelist()
    {
        return $this->hasOne(MasterMaterialPricelist::className(), ['item_code' => 'item_code', 'status_active' => 'status_active']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getKode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getProses()
    {
        return $this->hasOne(MasterProses::className(), ['code' => 'proses_code']);
    }

    public function typeOngkos($type_ongkos)
    {
        $typeOngkos = DataList::listTypeOngkos();
        return $typeOngkos[$type_ongkos];
    }

    public function newTotalOrder($param)
    {
        $total_order=0;
        if(!empty($param->proses_code)){
            $total_order = $param->new_harga_jual_1;
        }else{
            if(!empty($param->qty_order_1)){
                $total_order += $param->qty_order_1 * $param->new_harga_jual_1;
            }
            if(!empty($param->qty_order_2)){
                $total_order += $param->qty_order_2 * $param->new_harga_jual_2;
            }
        }
        return $total_order;
    }
}
