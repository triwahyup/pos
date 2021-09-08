<?php

namespace app\modules\master\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_mesin".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $jenis
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterMesin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_mesin';
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
            [['name', 'jenis'], 'required'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'jenis'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 128],
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
            'jenis' => 'Jenis',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function newcode()
    {
        $model = MasterMesin::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterMesin::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -3);
        }
        return (string)'MSN-'.sprintf('%03s', ($total+1));
    }
}
