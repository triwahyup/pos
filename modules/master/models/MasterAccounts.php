<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterAccountsDetail;
use app\modules\master\models\TempMasterAccountsDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_accounts".
 *
 * @property string $code
 * @property string|null $name
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterAccounts extends \yii\db\ActiveRecord
{
    public $detail_id;
    public $detail_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_accounts';
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
            [['code', 'name'], 'required'],
            [['status', 'created_at', 'updated_at', 'detail_id'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name', 'detail_name'], 'string', 'max' => 128],
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'detail_name' => 'Masukkan detail account:',
        ];
    }

    public function getDetails()
    {
        return $this->hasMany(MasterAccountsDetail::className(), ['accounts_code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempMasterAccountsDetail::className(), ['accounts_code' => 'code']);
    }
}
