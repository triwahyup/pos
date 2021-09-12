<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "temp_master_kode_akun_detail".
 *
 * @property int $id
 * @property string|null $code
 * @property int|null $urutan
 * @property string|null $name
 * @property int $user_id
 */
class TempMasterKodeAkunDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_kode_akun_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['code'], 'string', 'max' => 3],
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
            'code' => 'Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'user_id' => 'User ID',
        ];
    }

    public function getCount()
    {
        return TempMasterKodeAkunDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempMasterKodeAkunDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }
}
