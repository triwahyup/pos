<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_kabupaten".
 *
 * @property string $id
 * @property string $provinsi_id
 * @property string $name
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property MasterKecamatan[] $masterKecamatans
 * @property MasterProvinsi $provinsi
 */
class MasterKabupaten extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kabupaten';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'provinsi_id', 'name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 4],
            [['provinsi_id'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 128],
            [['id'], 'unique'],
            [['provinsi_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterProvinsi::className(), 'targetAttribute' => ['provinsi_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provinsi_id' => 'Provinsi ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[MasterKecamatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterKecamatans()
    {
        return $this->hasMany(MasterKecamatan::className(), ['kabupaten_id' => 'id']);
    }

    /**
     * Gets query for [[Provinsi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvinsi()
    {
        return $this->hasOne(MasterProvinsi::className(), ['id' => 'provinsi_id']);
    }
}
