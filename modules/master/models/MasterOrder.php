<?php

namespace app\modules\master\models;

use Yii;
use app\modules\inventory\models\InventoryStockItem;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterOrderDetail;
use app\modules\master\models\MasterOrderDetailProduksi;
use app\modules\master\models\TempMasterOrderDetail;
use app\modules\master\models\TempMasterOrderDetailProduksi;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_order".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterOrder extends \yii\db\ActiveRecord
{
    public $item_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_order';
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
            [['name', 'type_order', 'total_order', 'total_biaya', 'grand_total'], 'required'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'total_order', 'total_biaya', 'grand_total'], 'number'],
            [['status', 'created_at', 'updated_at', 'type_order'], 'integer'],
            [['code', 'satuan_code', 'type_code', 'material_code', 'group_material_code', 'group_supplier_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['item_code'], 'string', 'max' => 7],
            [['name', 'keterangan'], 'string', 'max' => 128],
            [['code'], 'unique'],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'name' => 'Name',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterOrder::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function beforeSave($attribute)
    {
        $this->qty_order_1 = str_replace(',', '', $this->qty_order_1);
        $this->qty_order_2 = str_replace(',', '', $this->qty_order_2);
        $this->qty_order_3 = str_replace(',', '', $this->qty_order_3);
        $this->total_order = str_replace(',', '', $this->total_order);
        $this->total_biaya = str_replace(',', '', $this->total_biaya);
        $this->grand_total = str_replace(',', '', $this->grand_total);
        return parent::beforeSave($attribute);
    }

    public function getDetails()
    {
        return $this->hasMany(MasterOrderDetail::className(), ['order_code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempMasterOrderDetail::className(), ['order_code' => 'code']);
    }

    public function temps()
    {
        return TempMasterOrderDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(MasterOrderDetailProduksi::className(), ['order_code' => 'code']);
    }

    public function getTempsProduksi()
    {
        return $this->hasMany(TempMasterOrderDetailProduksi::className(), ['order_code' => 'code']);
    }

    public function tempsProduksi()
    {
        return TempMasterOrderDetailProduksi::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getTotalOrder()
    {
        $total_order=0;
        if(!empty($this->qty_order_1)){
            $harga_jual_1 = str_replace(',', '', $this->harga_jual_1);
            $total_order += $this->qty_order_1 * $harga_jual_1;
        }
        if(!empty($this->qty_order_2)){
            $harga_jual_2 = str_replace(',', '', $this->harga_jual_2);
            $total_order += $this->qty_order_2 * $harga_jual_2;
        }
        if(!empty($this->qty_order_3)){
            $harga_jual_3 = str_replace(',', '', $this->harga_jual_3);
            $total_order += $this->qty_order_3 * $harga_jual_3;
        }
        return $total_order;
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['item_code' => 'item_code']);
    }

    public function getInventoryStock()
    {
        return $this->hasOne(InventoryStockItem::className(), ['item_code' => 'item_code']);
    }
}