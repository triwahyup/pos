<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_kecamatan".
 *
 * @property string $id
 * @property string $kabupaten_id
 * @property string $name
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property MasterKabupaten $kabupaten
 * @property MasterKelurahan[] $masterKelurahans
 */
class MasterKecamatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kecamatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kabupaten_id', 'name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 7],
            [['kabupaten_id'], 'string', 'max' => 4],
            [['name'], 'string', 'max' => 128],
            [['id'], 'unique'],
            [['kabupaten_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterKabupaten::className(), 'targetAttribute' => ['kabupaten_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kabupaten_id' => 'Kabupaten ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Kabupaten]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKabupaten()
    {
        return $this->hasOne(MasterKabupaten::className(), ['id' => 'kabupaten_id']);
    }

    /**
     * Gets query for [[MasterKelurahans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKelurahans()
    {
        return $this->hasMany(MasterKelurahan::className(), ['kecamatan_id' => 'id']);
    }
}
