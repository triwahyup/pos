<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "temp_master_accounts_detail".
 *
 * @property int $id
 * @property int|null $urutan
 * @property string|null $name
 * @property int $user_id
 */
class TempMasterAccountsDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_accounts_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'user_id'], 'integer'],
            [['accounts_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'user_id' => 'User ID',
        ];
    }

    public function getCount()
    {
        return TempMasterAccountsDetail::find(['user_id'=> \Yii::$app->user->id])->all();
    }
}
