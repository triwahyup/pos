<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterPerson;

/**
 * This is the model class for table "temp_master_barang_pricelist".
 *
 * @property int $id
 * @property string $barang_code
 * @property string $supplier_code
 * @property int $urutan
 * @property string|null $name
 * @property string|null $um_1
 * @property string|null $um_2
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property int|null $status_active
 * @property int|null $user_id
 */
class TempMasterBarangPricelist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_barang_pricelist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['harga_beli_1', 'harga_beli_2'], 'safe'],
            [['urutan', 'status_active', 'user_id'], 'integer'],
            [['barang_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['um_1', 'um_2'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barang_code' => 'Barang Code',
            'supplier_code' => 'Supplier Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'status_active' => 'Status Active',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->harga_beli_1 = str_replace(',', '', $this->harga_beli_1);
        $this->harga_beli_2 = str_replace(',', '', $this->harga_beli_2);
        return parent::beforeSave($attribute);
    }

    public function getStatusActive()
    {
        $message = '';
        if($this->status_active == 1){
            $message = '<span class="text-label text-success">Pricelist Active</span>';
        }else{
            $message = '<span class="text-label text-default">Not Active</span>';
        }
        return $message;
    }

    public function getCount()
    {
        return TempMasterBarangPricelist::find()->where(['supplier_code'=>$this->supplier_code, 'user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempMasterBarangPricelist::find()->where(['supplier_code'=>$this->supplier_code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterBarang::className(), ['code' => 'barang_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }
}
