<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterBarang;
use app\modules\master\models\MasterSatuan;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_internal_detail".
 *
 * @property string $no_po
 * @property int $urutan
 * @property string|null $item_name
 * @property float|null $qty
 * @property float|null $harga_beli
 * @property float|null $total_order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseInternalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_internal_detail';
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
            [['no_po', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty', 'harga_beli', 'total_order'], 'number'],
            [['supplier_code', 'satuan_code'], 'string', 'max' => 3],
            [['um'], 'string', 'max' => 5],
            [['barang_code'], 'string', 'max' => 7],
            [['no_po'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 128],
            [['no_po', 'urutan'], 'unique', 'targetAttribute' => ['no_po', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_po' => 'No Po',
            'urutan' => 'Urutan',
            'item_name' => 'Item Name',
            'qty' => 'Qty',
            'harga_beli' => 'Harga Beli',
            'total_order' => 'Total Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
