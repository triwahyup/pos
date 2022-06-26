<?php

namespace app\modules\log\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int|null $user_id
 * @property string|null $type
 * @property string|null $ip
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['type', 'ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'type' => 'Type',
            'ip' => 'Ip',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
