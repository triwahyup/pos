<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterProvinsi;
use app\modules\master\models\MasterKabupaten;
use app\modules\master\models\MasterKecamatan;
use app\modules\master\models\MasterKelurahan;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_person".
 *
 * @property string $code
 * @property string $name
 * @property string|null $address
 * @property string|null $provinsi_id
 * @property string|null $kabupaten_id
 * @property string|null $kecamatan_id
 * @property string|null $kelurahan_id
 * @property string|null $contact_person
 * @property string|null $kode_pos
 * @property string|null $phone_1
 * @property string|null $phone_2
 * @property string|null $email
 * @property string|null $fax
 * @property string|null $npwp
 * @property int|null $type_user
 * @property int|null $term_in
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterPerson extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_person';
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
            [['type_user', 'name', 'contact_person', 'kode_pos', 'address', 'phone_1', 'email', 'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'term_in'], 'required'],
            [['type_user', 'term_in', 'npwp', 'kode_pos', 'status', 'created_at', 'updated_at'], 'integer'],
            [['keterangan'], 'string'],
            [['code'], 'string', 'max' => 3],
            [['name', 'address', 'contact_person'], 'string', 'max' => 128],
            [['provinsi_id'], 'string', 'max' => 2],
            [['kabupaten_id'], 'string', 'max' => 4],
            [['kecamatan_id'], 'string', 'max' => 7],
            [['kelurahan_id'], 'string', 'max' => 10],
            [['phone_1', 'phone_2'], 'string', 'max' => 16],
            [['email', 'fax'], 'string', 'max' => 32],
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
            'address' => 'Address',
            'provinsi_id' => 'Provinsi',
            'kabupaten_id' => 'Kabupaten',
            'kecamatan_id' => 'Kecamatan',
            'kelurahan_id' => 'Kelurahan',
            'contact_person' => 'Contact Person',
            'kode_pos' => 'Kode Pos',
            'phone_1' => 'Phone 1',
            'phone_2' => 'Phone 2',
            'email' => 'Email',
            'fax' => 'Fax',
            'npwp' => 'Npwp',
            'type_user' => 'Type Person',
            'term_in' => 'Term In',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterPerson::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterPerson::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getProvinsi()
    {
        return $this->hasOne(MasterProvinsi::className(), ['id' => 'provinsi_id']);
    }

    public function getKabupaten()
    {
        return $this->hasOne(MasterKabupaten::className(), ['id' => 'kabupaten_id']);
    }

    public function getKecamatan()
    {
        return $this->hasOne(MasterKecamatan::className(), ['id' => 'kecamatan_id']);
    }

    public function getKelurahan()
    {
        return $this->hasOne(MasterKelurahan::className(), ['id' => 'kelurahan_id']);
    }

    public function getTypePerson()
    {
        return $this->hasOne(MasterKode::className(), ['value' => 'type_user']);
    }

    public function validateData($name, $type_user)
    {
        $model = MasterPerson::findOne(['name' => $name, 'type_user' => $type_user]);
        return $model;
    }
}