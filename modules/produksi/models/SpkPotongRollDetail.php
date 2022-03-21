<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_potong_roll_detail".
 *
 * @property string $code
 * @property string $item_code
 * @property int $urutan
 * @property string|null $supplier_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property float|null $qty
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkPotongRollDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_potong_roll_detail';
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
            [['code', 'item_code', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'qty'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
            [['code', 'item_code', 'urutan'], 'unique', 'targetAttribute' => ['code', 'item_code', 'urutan']],
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
            'urutan' => 'Urutan',
            'supplier_code' => 'Supplier Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'qty' => 'Qty',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }
}
