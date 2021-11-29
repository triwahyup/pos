<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;

/**
 * This is the model class for table "temp_sales_order_detail_produksi".
 *
 * @property int $id
 * @property string|null $no_so
 * @property string|null $order_code
 * @property int|null $urutan
 * @property string|null $name
 * @property string|null $item_code
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property int|null $user_id
 */
class TempSalesOrderDetailProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_detail_produksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail_urutan', 'urutan', 'type', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'harga'], 'number'],
            [['no_so'], 'string', 'max' => 12],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['item_code'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'no_so' => 'No So',
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'item_code' => 'Item Code',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'user_id' => 'User ID',
        ];
    }

    public function getCount()
    {
        return TempSalesOrderDetailProduksi::find()->where(['detail_urutan' => $this->detail_urutan, 'user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempSalesOrderDetailProduksi::find()->where(['detail_urutan' => $this->detail_urutan, 'user_id'=> \Yii::$app->user->id])->all();
    }
    
    public function getOrderCode()
    {
        $temp = TempSalesOrderDetailProduksi::find()->where(['no_so' => $this->no_so])->one();
        return $temp->order_code;
    }
    
    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }
}
