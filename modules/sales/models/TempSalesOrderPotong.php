<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\sales\models\TempSalesOrderProses;

/**
 * This is the model class for table "temp_sales_order_potong".
 *
 * @property int $id
 * @property string $code
 * @property int $urutan
 * @property string|null $item_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_objek
 * @property float|null $jumlah_objek
 * @property string|null $keterangan_cetak
 * @property string|null $keterangan_potong
 * @property string|null $keterangan_pond
 * @property int|null $user_id
 */
class TempSalesOrderPotong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_potong';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'urutan'], 'required'],
            [['urutan', 'total_objek', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'jumlah_objek'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['keterangan_cetak', 'keterangan_potong', 'keterangan_pond'], 'string', 'max' => 128],
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
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_objek' => 'Total Objek',
            'jumlah_objek' => 'Jumlah Objek',
            'keterangan_cetak' => 'Keterangan Cetak',
            'keterangan_potong' => 'Keterangan Potong',
            'keterangan_pond' => 'Keterangan Pond',
            'user_id' => 'User ID',
        ];
    }

    public function getTemps()
    {
        return TempSalesOrderPotong::find()->where(['code'=>$this->code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempSalesOrderPotong::find()->where(['item_code'=>$this->item_code, 'user_id'=> \Yii::$app->user->id])->count();
    }

    public function getProsesTemps()
    {
        return TempSalesOrderProses::find()
            ->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'potong_id'=>$this->urutan, 'user_id'=> \Yii::$app->user->id])
            ->all();
    }
}
