<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialPricelist;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use app\modules\sales\models\TempSalesOrderPotong;
use app\modules\sales\models\TempSalesOrderProses;

/**
 * This is the model class for table "temp_sales_order_item".
 *
 * @property int $id
 * @property string $code
 * @property int $urutan
 * @property string $item_code
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
 * @property int|null $total_potong
 * @property float|null $total_order
 * @property string|null $keterangan
 * @property int|null $user_id
 */
class TempSalesOrderItem extends \yii\db\ActiveRecord
{
    public $item_name;
    public $bahan_item_name;
    public $bahan_item_code;
    public $bahan_supplier_code;
    public $bahan_qty;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'total_potong', 'user_id'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'konversi_1', 'konversi_2', 'konversi_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'total_order', 'bahan_qty'], 'safe'],
            [['code'], 'string', 'max' => 12],
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
            'code' => 'Code',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
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
            'total_potong' => 'Total Potong',
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
        $this->total_potong = str_replace(',', '', $this->total_potong);
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
        $model = TempSalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.code'=>$this->code, 'supplier_code'=>$this->supplier_code, 'value'=>\Yii::$app->params['TYPE_KERTAS']])
            ->one();
        return $model;
    }

    public function getItemsMaterial()
    {
        $model = TempSalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.code'=>$this->code, 'value'=>\Yii::$app->params['TYPE_KERTAS']])
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
        return $this->hasOne(TempSalesOrderItem::className(), ['code' => 'code', 'item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }

    public function getTemps()
    {
        return TempSalesOrderItem::find()->where(['code'=>$this->code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempSalesOrderItem::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTempPotongs()
    {
        return $this->hasMany(TempSalesOrderPotong::className(), ['code' => 'code', 'item_code' => 'item_code', 'supplier_code' => 'supplier_code']);
    }

    public function getTempProses()
    {
        $temp = TempSalesOrderProses::find()->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'user_id'=> \Yii::$app->user->id])->all();
        return $temp;
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

    public static function up_produksi($qty, $up)
    {
        $total = str_replace(',','', $qty);
        $total_lb = $total * 500;
        $total_up = $total_lb * ($up / 100);
        
        return [
            'total' => $total,
            'total_lb' => $total_lb,
            'total_up' => $total_up,
        ];
    }
}
