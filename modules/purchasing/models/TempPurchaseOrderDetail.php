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
            [['qty_order', 'harga_beli', 'harga_jual', 'ppn', 'total_order'], 'number'],
            [['no_po'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan'], 'string', 'max' => 5],
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
            'satuan' => 'Satuan',
            'qty_order' => 'Qty',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
            'ppn' => 'Ppn',
            'total_order' => 'Total',
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
}
