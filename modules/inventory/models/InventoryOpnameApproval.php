<?php

namespace app\modules\inventory\models;

use Yii;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\master\models\Profile;
use app\modules\inventory\models\InventoryOpname;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_opname_approval".
 *
 * @property string $code
 * @property int $urutan
 * @property int|null $user_id
 * @property string|null $typeuser_code
 * @property string|null $comment
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryOpnameApproval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_opname_approval';
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
            [['code'], 'string', 'max' => 12],
            [['typeuser_code'], 'string', 'max' => 3],
            [['comment'], 'string', 'max' => 64],
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
            'comment' => 'Comment',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getOpname()
    {
        return $this->hasOne(InventoryOpname::className(), ['code' => 'code']);
    }

    public function getTypeUser()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'typeuser_code']);
    }

    public function getName()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProfile()
    {
        $profile = [];
        if(empty($this->user_id)){
            if(!empty($this->typeuser_code)){
                $profile = Profile::findAll(['typeuser_code'=>$this->typeuser_code, 'status'=>1]);
            }
        }else{
            $profile = Profile::findAll(['user_id'=>$this->user_id, 'status'=>1]);
        }
        if($this->status == 1 && count($profile) == 0){
			$this->status = 5;
			$this->comment = 'USER NOT FOUND.';
            $this->save();
		}
        return $profile;
    }

    public function getStatusApproval()
    {
        $message = '';
        if($this->status == 1){
            $message = '<span class="text-label text-primary">Send Approval</span>';
        }else if($this->status == 2){
            $message = '<span class="text-label text-warning">Waiting Approval</span>';
        }else if($this->status == 3){
            $message = '<span class="text-label text-success">Success Approval</span>';
        }else if($this->status == 4){
            $message = '<span class="text-label text-danger">Rejected Approval</span>';
        }else if($this->status == 5){
            $message = '<span class="text-label text-danger">User not found.</span>';
        }
        return $message;
    }
}
