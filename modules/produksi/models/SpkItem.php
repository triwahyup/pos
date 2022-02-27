<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use app\modules\produksi\models\SpkPotong;
use app\modules\produksi\models\SpkProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_item".
 *
 * @property string $no_spk
 * @property int $urutan
 * @property string $item_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property string|null $group_supplier_code
 * @property string|null $group_material_code
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property int|null $total_potong
 * @property int|null $total_warna
 * @property string|null $satuan_ikat_code
 * @property int|null $lembar_ikat_1
 * @property int|null $lembar_ikat_2
 * @property int|null $lembar_ikat_3
 * @property string|null $lembar_ikat_um_1
 * @property string|null $lembar_ikat_um_2
 * @property string|null $lembar_ikat_um_3
 * @property float|null $jumlah_cetak
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_item';
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
            [['no_spk', 'urutan', 'item_code'], 'required'],
            [['urutan', 'total_potong', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'jumlah_cetak'], 'number'],
            [['no_spk'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['satuan_code', 'material_code', 'type_code', 'group_supplier_code', 'group_material_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3', 'lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'urutan', 'item_code'], 'unique', 'targetAttribute' => ['no_spk', 'urutan', 'item_code']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_spk' => 'No Spk',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'group_supplier_code' => 'Group Supplier Code',
            'group_material_code' => 'Group Material Code',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'total_potong' => 'Total Potong',
            'total_warna' => 'Total Warna',
            'satuan_ikat_code' => 'Satuan Ikat Code',
            'lembar_ikat_1' => 'Lembar Ikat 1',
            'lembar_ikat_2' => 'Lembar Ikat 2',
            'lembar_ikat_3' => 'Lembar Ikat 3',
            'lembar_ikat_um_1' => 'Lembar Ikat Um 1',
            'lembar_ikat_um_2' => 'Lembar Ikat Um 2',
            'lembar_ikat_um_3' => 'Lembar Ikat Um 3',
            'jumlah_cetak' => 'Jumlah Cetak',
            'keterangan' => 'Keterangan',
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

    public function getPotongs()
    {
        return $this->hasMany(SpkPotong::className(), ['no_spk' => 'no_spk', 'item_code' => 'item_code']);
    }

    public function getProses()
    {
        return $this->hasMany(SpkProses::className(), ['no_spk' => 'no_spk', 'item_code' => 'item_code']);
    }
}
