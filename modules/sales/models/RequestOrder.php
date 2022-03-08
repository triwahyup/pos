<?php

namespace app\modules\sales\models;

use Yii;

/**
 * This is the model class for table "request_order".
 *
 * @property string $no_request
 * @property string|null $tgl_request
 * @property string|null $no_spk
 * @property string|null $keterangan
 * @property int|null $user_id
 * @property int|null $post
 * @property int|null $status_approval
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class RequestOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_request'], 'required'],
            [['tgl_request'], 'safe'],
            [['user_id', 'post', 'status_approval', 'status', 'created_at', 'updated_at'], 'integer'],
            [['no_request', 'no_spk'], 'string', 'max' => 12],
            [['keterangan'], 'string', 'max' => 128],
            [['no_request'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_request' => 'No Request',
            'tgl_request' => 'Tgl Request',
            'no_spk' => 'No Spk',
            'keterangan' => 'Keterangan',
            'user_id' => 'User ID',
            'post' => 'Post',
            'status_approval' => 'Status Approval',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
