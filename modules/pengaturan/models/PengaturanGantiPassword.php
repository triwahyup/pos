<?php
namespace app\modules\pengaturan\models;

use Yii;
use yii\base\Model;
use app\models\User;

class PengaturanGantiPassword extends Model
{
    public $current_password;
	public $new_password;
	public $retype_new_password;

    public function rules()
	{
		return [
			[['current_password', 'new_password', 'retype_new_password'], 'required'],
			[['current_password', 'new_password', 'retype_new_password'], 'string', 'max' => 18],
			['current_password', 'validatePassword'],
			['retype_new_password', 'compare', 'compareAttribute' => 'new_password'],
		];
	}

    public function attributeLabels()
	{
		return [
			'current_password' => 'Masukkan password saat ini',
			'new_password' => 'Masukkan password baru',
			'retype_new_password' => 'Ulangi password baru',
		];
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