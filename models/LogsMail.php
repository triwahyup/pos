<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "logs_mail".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $email
 * @property string|null $cc
 * @property string|null $bcc
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class LogsMail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs_mail';
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
            [['email', 'cc', 'bcc', 'subject', 'body', 'keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'email' => 'Email',
            'cc' => 'Cc',
            'bcc' => 'Bcc',
            'subject' => 'Subject',
            'body' => 'Body',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
