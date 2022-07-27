<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialPricelist;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;

/**
 * This is the model class for table "temp_request_order_item".
 *
 * @property int $id
 * @property string|null $no_request
 * @property int|null $urutan
 * @property string|null $item_code
 * @property string|null $supplier_code
 * @property string|null $satuan_code
 * @property string|null $material_code
 * @property string|null $type_code
 * @property float|null $qty_order_1
 * @property float|null $qty_order_2
 * @property float|null $qty_order_3
 * @property float|null $qty_up
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
 * @property int|null $user_id
 */
class TempRequestOrderItem extends \yii\db\ActiveRecord
{
    public $item_name;
    public $bahan_item_name;
    public $bahan_item_code;
    public $bahan_supplier_code;
    public $bahan_qty;
    public $type_qty;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_request_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'user_id', 'type_qty'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'qty_up', 'konversi_1', 'konversi_2', 'konversi_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'total_order', 'bahan_qty'], 'safe'],
            [['no_request'], 'string', 'max' => 12],
            [['item_code', 'bahan_item_code'], 'string', 'max' => 7],
            [['supplier_code', 'satuan_code', 'material_code', 'type_code', 'bahan_supplier_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['keterangan', 'item_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_request' => 'No. Request',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'material_code' => 'Material Code',
            'type_code' => 'Type Code',
            'qty_order_1' => 'Qty Order 1',
            'qty_order_2' => 'Qty Order 2',
            'qty_order_3' => 'Qty Order 3',
            'qty_up' => 'Qty Up',
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
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->qty_order_1 = str_replace(',', '', $this->qty_order_1);
        $this->qty_order_2 = str_replace(',', '', $this->qty_order_2);
        $this->qty_order_3 = str_replace(',', '', $this->qty_order_3);
        return parent::beforeSave($attribute);
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getItemBahan()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'bahan_item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public function getItemMaterial()
    {
        $model = TempRequestOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.no_request'=>$this->no_request, 'supplier_code'=>$this->supplier_code, 'value'=>\Yii::$app->params['TYPE_KERTAS']])
            ->one();
        return $model;
    }

    public function getItemsMaterial()
    {
        $model = TempRequestOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.no_request'=>$this->no_request, 'value'=>\Yii::$app->params['TYPE_KERTAS']])
            ->all();
        return $model;
    }

    public $status_active=1;
    public function getItemPricelist()
    {
        return $this->hasOne(MasterMaterialPricelist::className(), ['item_code' => 'item_code', 'status_active' => 'status_active']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }

    public function getItemTemp()
    {
        return $this->hasOne(TempRequestOrderItem::className(), ['no_request' => 'no_request', 'item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }

    public function getTemps()
    {
        return TempRequestOrderItem::find()->where(['no_request'=>$this->no_request, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempRequestOrderItem::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTotalOrder()
    {
        $total_order=0;
        if(!empty($this->qty_order_1)){
            $total_order += $this->qty_order_1 * $this->harga_jual_1;
        }
        if(!empty($this->qty_order_2)){
            $total_order += $this->qty_order_2 * $this->harga_jual_2;
        }
        return $total_order;
    }
}