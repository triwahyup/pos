<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterCoaDetail;
use app\modules\master\models\TempMasterCoaDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_coa".
 *
 * @property string $code
 * @property string|null $name
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterCoa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_coa';
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
            [['code', 'name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 3],
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDetails()
    {
        return $this->hasMany(MasterCoaDetail::className(), ['code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempMasterCoaDetail::className(), ['code' => 'code']);
    }

    public function temps()
    {
        return TempMasterCoaDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }
}
