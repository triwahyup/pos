<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\modules\pengaturan\models\PengaturanApprovalDetail;
use app\modules\pengaturan\models\TempPengaturanApprovalDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pengaturan_approval".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $slug
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PengaturanApproval extends \yii\db\ActiveRecord
{
    public $type;
    public $approval;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengaturan_approval';
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
            [['name'], 'required'],
            [['type', 'approval'], 'safe'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 8],
            [['name', 'slug'], 'string', 'max' => 64],
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
            'name' => 'Name',
            'slug' => 'Slug',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type' => 'Type',
            'approval' => 'User Approve',
        ];
    }

    public function newcode()
    {
        $model = PengaturanApproval::find()->count();
        $total=0;
        if($model > 0){
            $model = PengaturanApproval::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -3);
        }
        return (string)'APP-'.sprintf('%03s', ($total+1));
    }

    public function getDetails()
    {
        return $this->hasMany(PengaturanApprovalDetail::className(), ['approval_code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempPengaturanApprovalDetail::className(), ['approval_code' => 'code']);
    }

    public function temps()
    {
        return TempPengaturanApprovalDetail::find()->where(['user_create' => \Yii::$app->user->id])->all();
    }
}
