<?php

namespace app\modules\master\models;

use Yii;

use app\modules\master\models\TempMasterOrderDetailProduksi;

/**
 * This is the model class for table "temp_master_order_detail".
 *
 * @property int $id
 * @property string|null $order_code
 * @property int|null $urutan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $total_warna
 * @property float|null $harga_cetak
 * @property int|null $user_id
 */
class TempMasterOrderDetail extends \yii\db\ActiveRecord
{
    public $item_name;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_order_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'harga_cetak', 'total_potong', 'total_objek', 'total_warna', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3'], 'safe'],
            [['order_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['jumlah_cetak', 'jumlah_objek'], 'number'],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
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
            'order_code' => 'Order Code',
            'urutan' => 'Urutan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'total_potong' => 'Potong',
            'total_objek' => 'Objek',
            'total_warna' => 'Jumlah Warna',
            'harga_cetak' => 'Harga Cetak',
            'user_id' => 'User ID',
        ];
    }

    public function getTmps()
    {
        return TempMasterOrderDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    
    public function getDetailsProduksi()
    {
        return $this->hasMany(TempMasterOrderDetailProduksi::className(), ['order_code' => 'order_code']);
    }
}
