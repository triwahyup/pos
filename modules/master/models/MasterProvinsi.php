<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_provinsi".
 *
 * @property string $id
 * @property string $name
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property MasterKabupaten[] $masterKabupatens
 */
class MasterProvinsi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_provinsi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 128],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[MasterKabupatens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKabupatens()
    {
        return $this->hasMany(MasterKabupaten::className(), ['provinsi_id' => 'id']);
    }
}
