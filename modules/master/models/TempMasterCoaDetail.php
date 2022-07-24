<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "temp_master_coa_detail".
 *
 * @property string|null $code
 * @property string|null $name
 * @property int|null $user_id
 */
class TempMasterCoaDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_coa_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['urutan'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 128],
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
            'user_id' => 'User ID',
        ];
    }

    public function getTmps()
    {
        return TempMasterCoaDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function urutan($urutan)
    {
        return TempMasterCoaDetail::find()->where(['urutan'=> $urutan])->one();
    }
}