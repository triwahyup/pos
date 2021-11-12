<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\produksi\models\SpkRequestItemApproval;
use app\modules\produksi\models\SpkRequestItemDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_request_item".
 *
 * @property string $no_request
 * @property string|null $tgl_request
 * @property string|null $no_spk
 * @property string|null $keterangan
 * @property int|null $status_approval
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkRequestItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_request_item';
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
            [['no_spk', 'keterangan'], 'required'],
            [['tgl_request'], 'safe'],
            [['status_approval', 'status', 'post', 'created_at', 'updated_at'], 'integer'],
            [['no_request', 'no_spk'], 'string', 'max' => 12],
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
            'no_spk' => 'No Spk',
            'keterangan' => 'Keterangan',
            'status_approval' => 'Status Approval',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = SpkRequestItem::find()->count();
        $total=0;
        if($model > 0){
            $model = SpkRequestItem::find()->orderBy(['no_request'=>SORT_DESC])->one();
            $total = (int)substr($model->no_request, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
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

    public function getDetails()
    {
        return $this->hasMany(SpkRequestItemDetail::className(), ['no_request' => 'no_request']);
    }

    public function getApprovals()
    {
        return $this->hasMany(SpkRequestItemApproval::className(), ['no_request' => 'no_request']);
    }
}
