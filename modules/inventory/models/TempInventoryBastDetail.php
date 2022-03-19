<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterSatuan;

/**
 * This is the model class for table "temp_inventory_bast_detail".
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $urutan
 * @property string|null $barang_code
 * @property string|null $name
 * @property string|null $supplier_code
 * @property string|null $satuan_code
 * @property float|null $qty
 * @property string|null $um
 * @property string|null $keterangan
 * @property int|null $user_id
 */
class TempInventoryBastDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_inventory_bast_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'user_id'], 'integer'],
            [['qty'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['barang_code'], 'string', 'max' => 7],
            [['name', 'keterangan'], 'string', 'max' => 128],
            [['supplier_code', 'satuan_code'], 'string', 'max' => 3],
            [['um'], 'string', 'max' => 5],
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
            'barang_code' => 'Barang Code',
            'name' => 'Name',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'qty' => 'Qty',
            'um' => 'Um',
            'keterangan' => 'Keterangan',
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
        return TempInventoryBastDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempInventoryBastDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }
}
