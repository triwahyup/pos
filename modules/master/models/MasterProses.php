<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_proses".
 *
 * @property string $code
 * @property string|null $name
 * @property float|null $harga
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_proses';
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
            [['harga', 'index'], 'safe'],
            [['status', 'created_at', 'updated_at', 'type'], 'integer'],
            [['code', 'mesin_type'], 'string', 'max' => 3],
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
            'mesin_type' => 'Type Mesin',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->harga = str_replace(',', '', $this->harga);
        return parent::beforeSave($attribute);
    }

    public function generateCode()
    {
        $model = MasterProses::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterProses::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getTypeOngkos()
    {
        return ($this->type == 1) ? 'Cetak' : 'Pond';
    }

    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'mesin_type']);
    }
}
