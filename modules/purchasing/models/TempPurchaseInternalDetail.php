<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterSatuan;


/**
 * This is the model class for table "temp_purchase_internal_detail".
 *
 * @property int $id
 * @property string $no_po
 * @property int $urutan
 * @property string|null $item_name
 * @property float|null $qty
 * @property float|null $harga_beli
 * @property float|null $total_order
 * @property int|null $user_id
 */
class TempPurchaseInternalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_purchase_internal_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'urutan', 'user_id'], 'integer'],
            [['qty', 'harga_beli', 'total_order'], 'safe'],
            [['supplier_code', 'satuan_code'], 'string', 'max' => 3],
            [['um'], 'string', 'max' => 5],
            [['barang_code'], 'string', 'max' => 7],
            [['no_po'], 'string', 'max' => 12],
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
            'no_po' => 'No Pi',
            'urutan' => 'Urutan',
            'item_name' => 'Item Name',
            'qty' => 'Qty',
            'harga_beli' => 'Harga Beli',
            'total_order' => 'Total Order',
            'user_id' => 'User ID',
        ];
    }

    public function getBarang()
    {
        return $this->hasOne(MasterBarang::className(), ['code' => 'barang_code']);
    }
    
    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public function getCount()
    {
        return TempPurchaseInternalDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempPurchaseInternalDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function getTotalBeli()
    {
        $total_order=0;
        if(!empty($this->qty)){
            $harga_beli = str_replace(',', '', $this->harga_beli);
            $total_order += $this->qty * $harga_beli;
        }
        return $total_order;
    }

    public function beforeSave($attribute)
    {
        $this->harga_beli = str_replace(',', '', $this->harga_beli);
        $this->qty = str_replace(',', '', $this->qty);
        return parent::beforeSave($attribute);
    }
}
