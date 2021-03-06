<?php

namespace app\modules\master\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_coa_detail".
 *
 * @property string $code
 * @property string|null $name
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterCoaDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_coa_detail';
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
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['urutan'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 128],
            [['code', 'urutan'], 'unique', 'targetAttribute' => ['code', 'urutan']],
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
        ];
    }
}
