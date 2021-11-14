<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use app\modules\produksi\models\SpkDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_request_item_detail".
 *
 * @property string $no_request
 * @property int $urutan
 * @property string $no_spk
 * @property string $order_code
 * @property string|null $item_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $group_supplier_code
 * @property string|null $group_material_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_potong
 * @property int|null $total_objek
 * @property int|null $total_warna
 * @property int|null $lembar_ikat
 * @property int|null $lembar_ikat_type
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property float|null $harga_cetak
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $jumlah_cetak
 * @property float|null $jumlah_objek
 * @property float|null $total_order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkRequestItemDetail extends \yii\db\ActiveRecord
{
    public $item_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_request_item_detail';
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
            [['urutan', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat', 'lembar_ikat_type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'harga_cetak', 'qty_order_1', 'qty_order_2', 'qty_order_3', 'jumlah_cetak', 'jumlah_objek', 'total_order'], 'number'],
            [['no_request', 'no_spk'], 'string', 'max' => 12],
            [['order_code', 'satuan_code', 'material_code', 'type_code', 'group_supplier_code', 'group_material_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['item_name'], 'string', 'max' => 128],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['no_request', 'urutan'], 'unique', 'targetAttribute' => ['no_request', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_request' => 'No Request',
            'urutan' => 'Urutan',
            'no_spk' => 'No Spk',
            'order_code' => 'Order Code',
            'item_code' => 'Item Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'group_supplier_code' => 'Group Supplier Code',
            'group_material_code' => 'Group Material Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Total Potong',
            'total_objek' => 'Total Objek',
            'total_warna' => 'Total Warna',
            'lembar_ikat' => 'Lembar Ikat',
            'lembar_ikat_type' => 'Lembar Ikat Type',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'harga_cetak' => 'Harga Cetak',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'jumlah_cetak' => 'Jumlah Cetak',
            'jumlah_objek' => 'Jumlah Objek',
            'total_order' => 'Total Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCount()
    {
        return SpkRequestItemDetail::find()->where(['no_request'=> $this->no_request])->count();
    }

    public function getSpkDetail()
    {
        return $this->hasOne(SpkDetail::className(), ['no_spk' => 'no_spk']);
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code']);
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

    public function jumlahProses()
    {
        $konversi = $this->stock->satuanTerkecil($this->item_code, [
            0 => $this->qty_order_1,
            1 => $this->qty_order_2
        ]);
        $this->jumlah_cetak = $konversi * $this->total_potong;
        $this->jumlah_objek = $this->jumlah_cetak * $this->total_objek;
        return true;
    }
}
