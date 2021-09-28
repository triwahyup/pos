<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;

/**
 * This is the model class for table "temp_purchase_order_detail".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $no_po
 * @property int|null $urutan
 * @property string|null $item_code
 * @property string|null $name
 * @property string|null $satuan
 * @property float|null $qty_order
 * @property float|null $harga_beli
 * @property float|null $harga_jual
 * @property float|null $ppn
 * @property float|null $total_order
 */
class TempPurchaseOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_purchase_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'urutan'], 'integer'],
            [['qty_order_1', 'qty_order_2', 'qty_order_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'ppn', 'total_order'], 'safe'],
            [['no_po'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan_code'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'no_po' => 'No Po',
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'name' => 'Name',
            'um_1' => 'UM 1',
            'um_2' => 'UM 2',
            'um_3' => 'UM 3',
            'qty_order_1' => 'Qty 1',
            'qty_order_2' => 'Qty 2',
            'qty_order_3' => 'Qty 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'ppn' => 'Ppn',
            'total_order' => 'Total',
            'satuan' => 'Satuan',
        ];
    }

    public function getCount()
    {
        return TempPurchaseOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempPurchaseOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function beforeSave($attribute)
    {
        $this->harga_beli_1 = str_replace(',', '', $this->harga_beli_1);
        $this->harga_beli_2 = str_replace(',', '', $this->harga_beli_2);
        $this->harga_beli_3 = str_replace(',', '', $this->harga_beli_3);
        $this->harga_jual_1 = str_replace(',', '', $this->harga_jual_1);
        $this->harga_jual_2 = str_replace(',', '', $this->harga_jual_2);
        $this->harga_jual_3 = str_replace(',', '', $this->harga_jual_3);
        $this->qty_order_1 = str_replace(',', '', $this->qty_order_1);
        $this->qty_order_2 = str_replace(',', '', $this->qty_order_2);
        $this->qty_order_3 = str_replace(',', '', $this->qty_order_3);
        return parent::beforeSave($attribute);
    }

    public function getTotalBeli()
    {
        $total_order=0;
        if(!empty($this->qty_order_1)){
            $harga_beli_1 = str_replace(',', '', $this->harga_beli_1);
            $total_order += $this->qty_order_1 * $harga_beli_1;
        }
        if(!empty($this->qty_order_2)){
            $harga_beli_2 = str_replace(',', '', $this->harga_beli_2);
            $total_order += $this->qty_order_2 * $harga_beli_2;
        }
        if(!empty($this->qty_order_3)){
            $harga_beli_3 = str_replace(',', '', $this->harga_beli_3);
            $total_order += $this->qty_order_3 * $harga_beli_3;
        }

        if(!empty($this->ppn)){
            $ppn = $total_order / ($this->ppn*100);
            $total_order += $ppn;
        }
        return $total_order;
    }
}
