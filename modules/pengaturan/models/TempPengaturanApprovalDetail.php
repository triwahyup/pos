<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterKode;

/**
 * This is the model class for table "temp_pengaturan_approval_detail".
 *
 * @property string|null $approval_code
 * @property int|null $urutan
 * @property int $id
 * @property int|null $user_create
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
            [['urutan', 'user_create', 'user_id'], 'integer'],
            [['approval_code', 'typeuser_code'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'approval_code' => 'Approval Code',
            'urutan' => 'Urutan',
            'id' => 'ID',
            'user_create' => 'User Create',
            'user_id' => 'User ID',
            'typeuser_code' => 'Typeuser Code',
        ];
    }

    public function getCount()
    {
        return TempPengaturanApprovalDetail::find(['user_create'=> \Yii::$app->user->id])->all();
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getTypeUser()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'typeuser_code']);
    }
}
