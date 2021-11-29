<?php

namespace app\modules\master\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_biaya_produksi".
 *
 * @property string $code
 * @property string|null $name
 * @property float|null $harga
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterBiayaProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_biaya_produksi';
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
            [['name', 'type', 'harga'], 'required'],
            [['harga'], 'safe'],
            [['status', 'created_at', 'updated_at', 'type'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name', 'keterangan'], 'string', 'max' => 128],
            [['code'], 'unique'],
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
            'name' => 'Name',
            'harga' => 'Harga',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterBiayaProduksi::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterBiayaProduksi::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getTypeOngkos()
    {
        return ($this->type == 1) ? 'Cetak' : 'Pond';
    }

    public function beforeSave($attribute)
    {
        $this->harga = str_replace(',', '', $this->harga);
        return parent::beforeSave($attribute);
    }
}
