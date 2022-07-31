<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterPerson;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_barang_pricelist".
 *
 * @property string $barang_code
 * @property string $supplier_code
 * @property int $urutan
 * @property string|null $name
 * @property string|null $um_1
 * @property string|null $um_2
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property int|null $status
 * @property int|null $status_active
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterBarangPricelist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_barang_pricelist';
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
            [['barang_code', 'supplier_code', 'urutan'], 'required'],
            [['urutan', 'status', 'status_active', 'created_at', 'updated_at'], 'integer'],
            [['harga_beli_1', 'harga_beli_2'], 'number'],
            [['barang_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['um_1', 'um_2'], 'string', 'max' => 5],
            [['barang_code', 'supplier_code', 'urutan'], 'unique', 'targetAttribute' => ['barang_code', 'supplier_code', 'urutan']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_code' => 'Barang Code',
            'supplier_code' => 'Supplier Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'status' => 'Status',
            'status_active' => 'Status Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }
}
