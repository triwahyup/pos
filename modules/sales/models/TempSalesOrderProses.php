<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterBiayaProduksi;

/**
 * This is the model class for table "temp_sales_order_proses".
 *
 * @property int $id
 * @property string $code
 * @property string $item_code
 * @property int $detail_id
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
            [['id', 'code', 'item_code', 'detail_id', 'biaya_code'], 'required'],
            [['id', 'detail_id', 'type', 'user_id'], 'integer'],
            [['index', 'harga', 'total_biaya'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['biaya_code'], 'string', 'max' => 3],
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
            'code' => 'Code',
            'item_code' => 'Item Code',
            'detail_id' => 'Detail ID',
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
        return TempSalesOrderProses::find()->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'detail_id'=>$this->detail_id, 'user_id'=> \Yii::$app->user->id])->all();
    }
}
