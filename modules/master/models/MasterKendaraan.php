<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_kendaraan".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $type_code
 * @property string|null $nopol
 * @property string|null $no_handphone
 * @property string|null $no_sim
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterKendaraan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kendaraan';
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
            [['name', 'type_code', 'nopol', 'no_handphone', 'no_sim'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'type_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 64],
            [['nopol', 'no_handphone', 'no_sim'], 'string', 'max' => 16],
            [['keterangan'], 'string', 'max' => 128],
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
            'type_code' => 'Type',
            'nopol' => 'Nopol',
            'no_handphone' => 'No Handphone',
            'no_sim' => 'No Sim',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterKendaraan::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterKendaraan::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -3);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }
}
