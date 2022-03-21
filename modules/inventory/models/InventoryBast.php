<?php

namespace app\modules\inventory\models;

use Yii;
use app\modules\inventory\models\InventoryBastDetail;
use app\modules\inventory\models\TempInventoryBastDetail;
use app\modules\master\models\Profile;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "inventory_bast".
 *
 * @property string $code
 * @property string|null $date
 * @property int|null $user_id
 * @property string|null $type_code
 * @property string|null $keterangan
 * @property int|null $post
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class InventoryBast extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_bast';
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
            [['date', 'user_id', 'type_code'], 'required'],
            [['date'], 'safe'],
            [['user_id', 'post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 12],
            [['type_code'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['code'], 'unique'],
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
            'date' => 'Date',
            'user_id' => 'User',
            'type_code' => 'Type',
            'keterangan' => 'Keterangan',
            'post' => 'Post',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = InventoryBast::find()->count();
        $total=0;
        if($model > 0){
            $model = InventoryBast::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function beforeSave($attribute)
    {
        $this->date = date('Y-m-d', strtotime($this->date));
        return parent::beforeSave($attribute);
    }

    public function getType()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getDetails()
    {
        return $this->hasMany(InventoryBastDetail::className(), ['code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempInventoryBastDetail::className(), ['code' => 'code']);
    }

    public function temps()
    {
        return TempInventoryBastDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
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
}
