<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterProses;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_proses".
 *
 * @property string $code
 * @property string $item_code
 * @property string $proses_code
 * @property int|null $urutan
 * @property int|null $type 1: Cetak; 2: Potong;
 * @property float|null $index
 * @property float|null $harga
 * @property string|null $mesin_type
 * @property float|null $total_biaya
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_proses';
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
            [['code', 'item_code', 'proses_code'], 'required'],
            [['urutan', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['index', 'harga', 'total_biaya'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'mesin_type'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['code', 'item_code', 'proses_code'], 'unique', 'targetAttribute' => ['code', 'item_code', 'proses_code']],
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
            'item_code' => 'Item Code',
            'proses_code' => 'Proses Code',
            'urutan' => 'Urutan',
            'type' => 'Type',
            'index' => 'Index',
            'harga' => 'Harga',
            'mesin_type' => 'Mesin Type',
            'total_biaya' => 'Total Biaya',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getProsesProduksi()
    {
        return $this->hasOne(MasterProses::className(), ['code' => 'proses_code']);
    }

    public function totalBiaya($model)
    {
        $so_pots = SalesOrderPotong::findAll(['code'=>$model['code'], 'item_code'=>$model['item_code']]);
        $so_item = SalesOrderItem::findOne(['code'=>$model['code'], 'item_code'=>$model['item_code']]);
        if($this->type == 1){
            $konversi = $so_item->inventoryStock->satuanTerkecil($so_item->item_code, [
                0 => $so_item->qty_order_1,
                1 => $so_item->qty_order_2]);
            $totalBiaya = 0;
            foreach($so_pots as $val){
                $totalBiaya += $val->panjang * $val->lebar * $this->index * $konversi;
            }
            $this->total_biaya = $totalBiaya;
        }else{
            $this->total_biaya = $this->harga;
        }
    }
}
