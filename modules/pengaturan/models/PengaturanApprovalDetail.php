<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pengaturan_approval_detail".
 *
 * @property string $approval_code
 * @property int $urutan
 * @property int|null $user_id
 * @property string|null $typeuser_code
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PengaturanApprovalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengaturan_approval_detail';
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
            [['urutan', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['approval_code', 'typeuser_code'], 'string', 'max' => 8],
            [['approval_code', 'urutan'], 'unique', 'targetAttribute' => ['approval_code', 'urutan']],
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
            'user_id' => 'User ID',
            'typeuser_code' => 'Typeuser',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
