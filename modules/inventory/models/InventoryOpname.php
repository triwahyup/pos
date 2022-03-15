<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\master\models\Profile;
use app\modules\master\models\MasterPerson;
use app\modules\inventory\models\InventoryOpnameApproval;
use app\modules\inventory\models\InventoryOpnameDetail;
use app\modules\inventory\models\TempInventoryOpnameDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_opname".
 *
 * @property string $code
 * @property string|null $date
 * @property string|null $keterangan
 * @property int|null $post
 * @property int|null $status_approval
 * @property int|null $status
 * @property int|null $user_id
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryOpname extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_opname';
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
            [['date'], 'safe'],
            [['post', 'status_approval', 'status', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['supplier_code'], 'string', 'max' => 3],
            [['code'], 'string', 'max' => 12],
            [['keterangan'], 'string', 'max' => 128],
            [['code'], 'unique'],
            [['status_approval', 'post'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'supplier_code' => 'Supplier',
            'date' => 'Date',
            'keterangan' => 'Keterangan',
            'post' => 'Post',
            'status_approval' => 'Status Approval',
            'status' => 'Status',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = InventoryOpname::find()->count();
        $total=0;
        if($model > 0){
            $model = InventoryOpname::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }
    
    public function getDetails()
    {
        return $this->hasMany(InventoryOpnameDetail::className(), ['code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempInventoryOpnameDetail::className(), ['code' => 'code']);
    }

    public function temps()
    {
        return TempInventoryOpnameDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getApprovals()
    {
        return $this->hasMany(InventoryOpnameApproval::className(), ['code' => 'code']);
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
