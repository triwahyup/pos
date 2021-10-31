<?php

namespace app\modules\master\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterOrderDetailProduksi;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_order_detail".
 *
 * @property string $order_code
 * @property int $urutan
 * @property string|null $item_code
 * @property string|null $satuan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_warna
 * @property int|null $lembar_ikat
 * @property float|null $harga_jual
 * @property float|null $harga_cetak
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_order_detail';
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
            [['urutan', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat', 'status', 'created_at', 'updated_at', 'lembar_ikat_type'], 'integer'],
            [['panjang', 'lebar', 'harga_cetak', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'jumlah_cetak', 'jumlah_objek', 'total_order'], 'number'],
            [['order_code', 'satuan_code', 'type_code', 'material_code', 'group_material_code', 'group_supplier_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['item_code'], 'string', 'max' => 7],
            [['order_code', 'urutan'], 'unique', 'targetAttribute' => ['order_code', 'urutan']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'satuan_code' => 'Satuan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Potong',
            'total_objek' => 'Objek',
            'total_warna' => 'Jumlah Warna',
            'lembar_ikat' => 'Lembar Ikat',
            'harga_jual' => 'Harga Jual',
            'harga_cetak' => 'Harga Cetak',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code']);
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(MasterOrderDetailProduksi::className(), ['order_code' => 'order_code', 'item_code' => 'item_code']);
    }

    public function getTypeIkat()
    {
        $type = '';
        if($this->lembar_ikat_type==1){
            $type = $this->lembar_ikat.' SAP';
        }else if($this->lembar_ikat_type==2){
            $type = $this->lembar_ikat.' IKAT';
        }else if($this->lembar_ikat_type==3){
            $type = $this->lembar_ikat.' DOS';
        }
        return $type;
    }
}
