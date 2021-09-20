<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_satuan".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $type_code
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterSatuan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_satuan';
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
            [['name', 'type_code'], 'required'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
            [['type_code'], 'string', 'max' => 8],
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
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterSatuan::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterSatuan::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }
}
