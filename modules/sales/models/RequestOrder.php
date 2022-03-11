<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\Profile;
use app\modules\sales\models\RequestOrderApproval;
use app\modules\sales\models\RequestOrderItem;
use app\modules\sales\models\TempRequestOrderItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "request_order".
 *
 * @property string $no_request
 * @property string|null $tgl_request
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
            [['tgl_request'], 'required'],
            [['tgl_request'], 'safe'],
            [['user_id', 'post', 'status_approval', 'status', 'created_at', 'updated_at'], 'integer'],
            [['no_request'], 'string', 'max' => 12],
            [['keterangan'], 'string', 'max' => 128],
            [['no_request'], 'unique'],
            [['status'], 'default', 'value' => 1],
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
            'keterangan' => 'Keterangan',
            'user_id' => 'User Request',
            'post' => 'Post',
            'status_approval' => 'Status Approval',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->tgl_request = date('Y-m-d', strtotime($this->tgl_request));
        return parent::beforeSave($attribute);
    }

    public function generateCode()
    {
        $model = RequestOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = RequestOrder::find()->orderBy(['no_request'=>SORT_DESC])->one();
            $total = (int)substr($model->no_request, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getItems()
    {
        return $this->hasMany(RequestOrderItem::className(), ['no_request' => 'no_request']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempRequestOrderItem::className(), ['no_request' => 'no_request']);
    }

    public function temps()
    {
        return TempRequestOrderItem::findAll(['user_id' => \Yii::$app->user->id]);
    }

    public function getApprovals()
    {
        return $this->hasMany(RequestOrderApproval::className(), ['no_request' => 'no_request']);
    }

    public function getStatusPost()
    {
        $message = '';
        if($this->post == 1){
            $message = '<span class="text-label text-success">Sudah Post</span>';
        }else{
            $message = '<span class="text-label text-default">Belum Post</span>';
        }
        return $message;
    }

    public function getStatusApproval()
    {
        $message = '';
        if($this->status_approval == 1){
            $message = '<span class="text-label text-primary">Send Approval</span>';
        }else if($this->status_approval == 2){
            $message = '<span class="text-label text-success">Finish</span>';
        }else if($this->status_approval == 3){
            $message = '<span class="text-label text-danger">Rejected</span>';
        }else{
            $message = '<span class="text-label text-default">Not Send</span>';
        }
        return $message;
    }
}
