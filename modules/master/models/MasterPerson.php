<?php
namespace app\modules\master\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\master\models\MasterProvinsi;
use app\modules\master\models\MasterKabupaten;
use app\modules\master\models\MasterKecamatan;
use app\modules\master\models\MasterKelurahan;

/**
 * This is the model class for table "master_person".
 *
 * @property string $code
 * @property string $name
 * @property string|null $nik
 * @property string|null $nip
 * @property string|null $tgl_lahir
 * @property string|null $tempat_lahir
 * @property string|null $address
 * @property string|null $provinsi_id
 * @property string|null $kabupaten_id
 * @property string|null $kecamatan_id
 * @property string|null $kelurahan_id
 * @property string|null $kode_pos
 * @property string|null $phone_1
 * @property string|null $phone_2
 * @property string|null $email
 * @property string|null $fax
 * @property string|null $keterangan
 * @property int|null $type_user
 * @property string|null $masuk
 * @property string|null $keluar
 * @property string|null $tgl_jatuh_tempo
 * @property string|null $group_supplier_code
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
            [['code', 'name'], 'required'],
            [['tgl_lahir', 'masuk', 'keluar', 'tgl_jatuh_tempo'], 'safe'],
            [['type_user', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'golongan'], 'string', 'max' => 5],
            [['name', 'tempat_lahir', 'address', 'keterangan'], 'string', 'max' => 128],
            [['nik', 'nip', 'email', 'fax'], 'string', 'max' => 32],
            [['provinsi_id'], 'string', 'max' => 2],
            [['kabupaten_id'], 'string', 'max' => 4],
            [['kecamatan_id'], 'string', 'max' => 7],
            [['kelurahan_id'], 'string', 'max' => 10],
            [['kode_pos'], 'string', 'max' => 8],
            [['phone_1', 'phone_2'], 'string', 'max' => 16],
            [['group_supplier_code'], 'string', 'max' => 3],
            [['code'], 'unique'],
            [['email'], 'trim'],
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
            'nik' => 'NIK',
            'nip' => 'NIP',
            'tgl_lahir' => 'Tgl Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'address' => 'Address',
            'provinsi_id' => 'Provinsi',
            'kabupaten_id' => 'Kabupaten',
            'kecamatan_id' => 'Kecamatan',
            'kelurahan_id' => 'Kelurahan',
            'kode_pos' => 'Kode Pos',
            'phone_1' => 'Phone 1',
            'phone_2' => 'Phone 2',
            'email' => 'Email',
            'fax' => 'Fax',
            'keterangan' => 'Keterangan',
            'type_user' => 'Type User',
            'masuk' => 'Masuk',
            'keluar' => 'Keluar',
            'tgl_jatuh_tempo' => 'Tgl Jatuh Tempo',
            'group_supplier_code' => 'Group Supplier',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'golongan' => 'Golongan',
        ];
    }

    public function newcode()
    {
        $model = MasterPerson::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterPerson::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%05s', ($total+1));
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
}