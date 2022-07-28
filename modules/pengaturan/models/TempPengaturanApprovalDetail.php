<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterKode;

/**
 * This is the model class for table "temp_pengaturan_approval_detail".
 *
 * @property int $id
 * @property int|null $user_create
 * @property string|null $code
 * @property int|null $urutan
 * @property int|null $user_id
 * @property string|null $typeuser_code
 */
class TempPengaturanApprovalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_pengaturan_approval_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_create', 'urutan', 'user_id'], 'integer'],
            [['code', 'typeuser_code', 'type_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_create' => 'User Create',
            'code' => 'Code',
            'urutan' => 'Urutan',
            'user_id' => 'User ID',
            'typeuser_code' => 'Typeuser Code',
        ];
    }

    public function getCount()
    {
        return TempPengaturanApprovalDetail::find()->where(['user_create'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempPengaturanApprovalDetail::find()->where(['user_create'=> \Yii::$app->user->id])->all();
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getTypeUser()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'typeuser_code']);
    }

    public function getTypeMaterial()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }
}
