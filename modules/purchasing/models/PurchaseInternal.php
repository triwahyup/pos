<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\Profile;
use app\modules\purchasing\models\PurchaseInternalApproval;
use app\modules\purchasing\models\PurchaseInternalDetail;
use app\modules\purchasing\models\TempPurchaseInternalDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_internal".
 *
 * @property string $no_pi
 * @property string|null $tgl_pi
 * @property string|null $keterangan
 * @property float|null $total_order
 * @property int|null $user_id
 * @property int|null $user_request
 * @property int|null $status
 * @property int|null $status_approval
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseInternal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_internal';
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
            [['tgl_pi', 'user_request'], 'required'],
            [['tgl_pi', 'total_order'], 'safe'],
            [['keterangan'], 'string'],
            [['user_id', 'user_request', 'status', 'status_approval', 'created_at', 'updated_at'], 'integer'],
            [['no_pi'], 'string', 'max' => 12],
            [['no_pi'], 'unique'],
            [['status_approval'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_pi' => 'No PO Internal',
            'tgl_pi' => 'Tgl PO Internal',
            'keterangan' => 'Keterangan',
            'total_order' => 'Total Order',
            'user_id' => 'User ID',
            'user_request' => 'User Request',
            'status' => 'Status',
            'status_approval' => 'Status Approval',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->total_order = str_replace(',', '', $this->total_order);
        return parent::beforeSave($attribute);
    }

    public function generateCode()
    {
        $model = PurchaseInternal::find()->count();
        $total=0;
        if($model > 0){
            $model = PurchaseInternal::find()->orderBy(['no_pi'=>SORT_DESC])->one();
            $total = (int)substr($model->no_pi, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getRequest()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_request']);
    }

    public function getDetails()
    {
        return $this->hasMany(PurchaseInternalDetail::className(), ['no_pi' => 'no_pi']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempPurchaseInternalDetail::className(), ['no_pi' => 'no_pi']);
    }

    public function temps()
    {
        return TempPurchaseInternalDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getApprovals()
    {
        return $this->hasMany(PurchaseInternalApproval::className(), ['no_pi' => 'no_pi']);
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
