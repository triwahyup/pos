<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pengaturan_approval_detail".
 *
 * @property string $code
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
            [['code', 'urutan'], 'required'],
            [['urutan', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'typeuser_code', 'type_code'], 'string', 'max' => 3],
            [['code', 'urutan'], 'unique', 'targetAttribute' => ['code', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'urutan' => 'Urutan',
            'user_id' => 'User ID',
            'typeuser_code' => 'Typeuser Code',
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

    public function getTypeMaterial()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }
}
