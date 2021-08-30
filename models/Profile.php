<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string|null $code
 * @property string|null $name
 * @property string|null $address
 * @property string|null $tempat_lahir
 * @property string|null $tgl_lahir
 * @property string|null $email
 * @property string|null $phone_1
 * @property string|null $phone_2
 * @property string|null $tgl_masuk
 * @property string|null $foto
 * @property int|null $typeuser_id
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'typeuser_id'], 'integer'],
            [['tgl_lahir', 'tgl_masuk'], 'safe'],
            [['code'], 'string', 'max' => 8],
            [['name', 'email', 'foto'], 'string', 'max' => 128],
            [['address'], 'string', 'max' => 255],
            [['tempat_lahir'], 'string', 'max' => 64],
            [['phone_1', 'phone_2'], 'string', 'max' => 32],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'code' => 'Code',
            'name' => 'Name',
            'address' => 'Address',
            'tempat_lahir' => 'Tempat Lahir',
            'tgl_lahir' => 'Tgl Lahir',
            'email' => 'Email',
            'phone_1' => 'Phone 1',
            'phone_2' => 'Phone 2',
            'tgl_masuk' => 'Tgl Masuk',
            'foto' => 'Foto',
            'typeuser_id' => 'Typeuser ID',
        ];
    }
}
