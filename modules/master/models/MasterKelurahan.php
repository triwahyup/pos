<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_kelurahan".
 *
 * @property string $id
 * @property string $kecamatan_id
 * @property string $name
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property MasterKecamatan $kecamatan
 */
class MasterKelurahan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kelurahan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kecamatan_id', 'name'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 10],
            [['kecamatan_id'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['id'], 'unique'],
            [['kecamatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterKecamatan::className(), 'targetAttribute' => ['kecamatan_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kecamatan_id' => 'Kecamatan ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Kecamatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKecamatan()
    {
        return $this->hasOne(MasterKecamatan::className(), ['id' => 'kecamatan_id']);
    }
}
