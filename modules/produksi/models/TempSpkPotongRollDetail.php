<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;

/**
 * This is the model class for table "temp_spk_potong_roll_detail".
 *
 * @property int $id
 * @property string $code
 * @property string $item_code
 * @property int $urutan
 * @property string|null $supplier_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property float|null $qty
 * @property int|null $user_id
 */
class TempSpkPotongRollDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_spk_potong_roll_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'item_code', 'urutan'], 'required'],
            [['urutan', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'qty'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
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
            'urutan' => 'Urutan',
            'supplier_code' => 'Supplier',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'qty' => 'Qty',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->panjang = str_replace(',', '', $this->panjang);
        $this->lebar = str_replace(',', '', $this->lebar);
        $this->qty = str_replace(',', '', $this->qty);
        return parent::beforeSave($attribute);
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getCount()
    {
        return TempSpkPotongRollDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempSpkPotongRollDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }
}
