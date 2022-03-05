<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterProses;
use app\modules\sales\models\TempSalesOrderPotong;
use app\modules\sales\models\TempSalesOrderItem;

/**
 * This is the model class for table "temp_sales_order_proses".
 *
 * @property int $id
 * @property string $code
 * @property string $item_code
 * @property string $proses_code
 * @property int|null $type 1: Cetak; 2: Potong;
 * @property float|null $index
 * @property float|null $harga
 * @property float|null $total_biaya
 * @property string|null $keterangan
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
            [['code', 'item_code', 'proses_code'], 'required'],
            [['type', 'user_id'], 'integer'],
            [['index', 'harga', 'total_biaya'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'mesin_type'], 'string', 'max' => 3],
            [['keterangan'], 'safe'],
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
            'item_code' => 'Item Code',
            'proses_code' => 'Proses Code',
            'type' => 'Type',
            'index' => 'Index',
            'harga' => 'Harga',
            'total_biaya' => 'Total Biaya',
            'keterangan' => 'Keterangan',
            'user_id' => 'User ID',
        ];
    }

    public function getTemps()
    {
        return TempSalesOrderProses::find()->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getProsesProduksi()
    {
        return $this->hasOne(MasterProses::className(), ['code' => 'proses_code']);
    }
    
    public function prosesProduksi($proses_code)
    {
        return MasterProses::findOne(['code'=>$proses_code]);
    }

    public function totalBiaya($model)
    {
        $tempPotongs = TempSalesOrderPotong::findAll(['code'=>$model['code'], 'item_code'=>$model['item_code']]);
        $tempItem = TempSalesOrderItem::findOne(['code'=>$model['code'], 'item_code'=>$model['item_code']]);
        if($this->type == 1){
            $konversi = $tempItem->inventoryStock->satuanTerkecil($tempItem->item_code, [
                0 => $tempItem->qty_order_1,
                1 => $tempItem->qty_order_2]);
            $totalBiaya = 0;
            foreach($tempPotongs as $val){
                $totalBiaya += $val->panjang * $val->lebar * $this->index * $konversi;
            }
            $this->total_biaya = $totalBiaya;
        }else{
            $this->total_biaya = $this->harga;
        }
    }
}
