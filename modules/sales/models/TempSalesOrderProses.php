<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\sales\models\TempSalesOrderPotong;
use app\modules\sales\models\TempSalesOrderItem;

/**
 * This is the model class for table "temp_sales_order_proses".
 *
 * @property string $code
 * @property string $item_code
 * @property int $potong_id
 * @property string $biaya_code
 * @property int|null $type 1: Cetak; 2: Potong;
 * @property float|null $index
 * @property float|null $harga
 * @property float|null $total_biaya
 * @property int|null $user_id
 */
class TempSalesOrderProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_proses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'item_code', 'potong_id', 'biaya_code'], 'required'],
            [['potong_id', 'type', 'user_id'], 'integer'],
            [['index', 'harga', 'total_biaya'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['biaya_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'item_code' => 'Item Code',
            'potong_id' => 'Potong ID',
            'biaya_code' => 'Biaya Code',
            'type' => 'Type',
            'index' => 'Index',
            'harga' => 'Harga',
            'total_biaya' => 'Total Biaya',
            'user_id' => 'User ID',
        ];
    }

    public function getTemps()
    {
        return TempSalesOrderProses::find()->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'potong_id'=>$this->potong_id, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getBiayaProduksi()
    {
        return $this->hasOne(MasterBiayaProduksi::className(), ['code' => 'biaya_code']);
    }
    
    public function biayaProduksi($biaya_code)
    {
        return MasterBiayaProduksi::findOne(['code'=>$biaya_code]);
    }

    public function totalBiaya($model)
    {
        $tempPotong = TempSalesOrderPotong::findOne(['code'=>$model['code'], 'item_code'=>$model['item_code'], 'urutan'=>$model['urutan']]);
        $tempItem = TempSalesOrderItem::findOne(['code'=>$model['code'], 'item_code'=>$model['item_code']]);
        if($this->type == 1){
            $konversi = $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                0 => $tempItem->qty_order_1,
                1 => $tempItem->qty_order_2]);
            $this->total_biaya = $tempPotong->panjang * $tempPotong->lebar * $this->index * $konversi;
        }else{
            $this->total_biaya = $this->harga;
        }
        return true;
    }
}
