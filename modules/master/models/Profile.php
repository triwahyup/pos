<?php

namespace app\modules\master\models;

use Yii;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterProvinsi;
use app\modules\master\models\MasterKabupaten;
use app\modules\master\models\MasterKecamatan;
use app\modules\master\models\MasterKelurahan;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string|null $name
 * @property string|null $nik
 * @property string|null $nip
 * @property string|null $tgl_lahir
 * @property string|null $tempat_lahir
 * @property string|null $alamat
 * @property string|null $provinsi_id
 * @property string|null $kabupaten_id
 * @property string|null $kecamatan_id
 * @property string|null $kelurahan_id
 * @property string|null $kode_pos
 * @property string|null $phone_1
 * @property string|null $phone_2
 * @property string|null $email
 * @property string|null $keterangan
 * @property string|null $tgl_masuk
 * @property string|null $tgl_keluar
 * @property string|null $golongan
 * @property string|null $foto
 * @property string|null $typeuser_code
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Profile extends \yii\db\ActiveRecord
{
    public $username;
    public $password;
    public $current_password;
    public $new_password;
	public $retype_new_password;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
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
            [['user_id', 'name', 'nik', 'email', 'typeuser_code'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tgl_lahir', 'tgl_masuk', 'tgl_keluar', 'username', 'password', 'current_password', 'new_password', 'retype_new_password'], 'safe'],
            [['name', 'tempat_lahir', 'alamat', 'keterangan', 'foto'], 'string', 'max' => 128],
            [['nik', 'nip', 'email'], 'string', 'max' => 32],
            [['provinsi_id'], 'string', 'max' => 2],
            [['kabupaten_id'], 'string', 'max' => 4],
            [['kecamatan_id'], 'string', 'max' => 7],
            [['kelurahan_id'], 'string', 'max' => 10],
            [['kode_pos'], 'string', 'max' => 8],
            [['phone_1', 'phone_2', 'npwp'], 'string', 'max' => 16],
            [['password', 'new_password', 'retype_new_password'], 'string', 'max' => 18],
            [['golongan'], 'string', 'max' => 5],
            [['typeuser_code'], 'string', 'max' => 3],
            [['user_id'], 'unique'],
            // ['password', 'validatePassword'],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'name' => 'Name',
            'nik' => 'NIK',
            'nip' => 'NIP',
            'tgl_lahir' => 'Tgl Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'alamat' => 'Alamat',
            'provinsi_id' => 'Provinsi',
            'kabupaten_id' => 'Kabupaten',
            'kecamatan_id' => 'Kecamatan',
            'kelurahan_id' => 'Kelurahan',
            'kode_pos' => 'Kode Pos',
            'phone_1' => 'Phone 1',
            'phone_2' => 'Phone 2',
            'email' => 'Email',
            'keterangan' => 'Keterangan',
            'tgl_masuk' => 'Tgl Masuk',
            'tgl_keluar' => 'Tgl Keluar',
            'golongan' => 'Golongan',
            'foto' => 'Foto',
            'typeuser_code' => 'Type User',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'current_password' => 'Masukkan password saat ini',
            'new_password' => 'Masukkan password baru',
			'retype_new_password' => 'Ulangi password baru',
        ];
    }

    public function getTypeUser()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'typeuser_code']);
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(\Yii::$app->user->id);
			if (!$user || !$user->validatePassword($this->current_password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function validatespace($attribute, $params)
	{
		if (preg_match('/\s+/', $params)) {
			$this->addError($attribute, 'No white spaces allowed!');
		}
	}
}
