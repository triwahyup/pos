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
            [['name', 'type_code', 'type_satuan', 'um_1', 'composite'], 'required'],
            [['konversi_1', 'konversi_2', 'konversi_3'], 'number'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at', 'composite'], 'integer'],
            [['code', 'type_code', 'type_satuan'], 'string', 'max' => 3],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
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
            'type_code' => 'Type',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'konversi_1' => 'Konv 1',
            'konversi_2' => 'Konv 2',
            'konversi_3' => 'Konv 3',
        ];
    }

    public function generateCode()
    {
        $model = MasterSatuan::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterSatuan::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -3);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getTypeSatuan()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_satuan']);
    }
}
