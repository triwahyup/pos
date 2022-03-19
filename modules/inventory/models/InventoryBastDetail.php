<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_bast_detail".
 *
 * @property string $code
 * @property int $urutan
 * @property string|null $barang_code
 * @property string|null $name
 * @property string|null $supplier_code
 * @property string|null $satuan_code
 * @property float|null $qty
 * @property string|null $um
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryBastDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_bast_detail';
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
            [['code', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['barang_code'], 'string', 'max' => 7],
            [['name', 'keterangan'], 'string', 'max' => 128],
            [['supplier_code', 'satuan_code'], 'string', 'max' => 3],
            [['um'], 'string', 'max' => 5],
            [['code', 'urutan'], 'unique', 'targetAttribute' => ['code', 'urutan']],
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
            'urutan' => 'Urutan',
            'barang_code' => 'Barang Code',
            'name' => 'Name',
            'supplier_code' => 'Supplier Code',
            'satuan_code' => 'Satuan Code',
            'qty' => 'Qty',
            'um' => 'Um',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getBarang()
    {
        return $this->hasOne(MasterBarang::className(), ['code' => 'barang_code']);
    }
    
    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }
}
