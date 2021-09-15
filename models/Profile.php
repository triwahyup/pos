<?php

namespace app\models;

use Yii;
use app\modules\master\models\MasterKode;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string|null $name
 * @property string|null $foto
 * @property int|null $typeuser_code
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['typeuser_code'], 'string', 'max' => 8],
            [['name', 'foto'], 'string', 'max' => 128],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Name',
            'foto' => 'Foto',
            'typeuser_code' => 'Type User',
        ];
    }

    public function getTypeUser()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'typeuser_code']);
    }
}
