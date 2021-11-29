<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;

/**
 * This is the model class for table "temp_master_order_detail_produksi".
 *
 * @property int $id
 * @property string $order_code
 * @property int $urutan
 * @property string|null $name
 * @property string|null $biaya_produksi_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $type
 * @property float|null $harga
 * @property int|null $user_id
 */
class TempMasterOrderDetailProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_order_detail_produksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'detail_urutan', 'type', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'harga'], 'number'],
            [['order_code', 'biaya_produksi_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'biaya_produksi_code' => 'Biaya Produksi Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'type' => 'Type',
            'harga' => 'Harga',
            'user_id' => 'User ID',
        ];
    }

    public function getCount()
    {
        return TempMasterOrderDetailProduksi::find()->where(['detail_urutan' => $this->detail_urutan, 'user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempMasterOrderDetailProduksi::find()->where(['detail_urutan' => $this->detail_urutan, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }
}
