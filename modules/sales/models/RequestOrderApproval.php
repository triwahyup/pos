<?php

namespace app\modules\sales\models;

use Yii;

/**
 * This is the model class for table "request_order_approval".
 *
 * @property string $no_request
 * @property int $urutan
 * @property int|null $user_id
 * @property string|null $typeuser_code
 * @property string|null $comment
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class RequestOrderApproval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_order_approval';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_request', 'urutan'], 'required'],
            [['urutan', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['no_request'], 'string', 'max' => 12],
            [['typeuser_code'], 'string', 'max' => 3],
            [['comment'], 'string', 'max' => 64],
            [['no_request', 'urutan'], 'unique', 'targetAttribute' => ['no_request', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_request' => 'No Request',
            'urutan' => 'Urutan',
            'user_id' => 'User ID',
            'typeuser_code' => 'Typeuser Code',
            'comment' => 'Comment',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
