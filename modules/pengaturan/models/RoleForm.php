<?php
namespace app\modules\pengaturan\models;

use Yii;
use yii\base\Model;
use app\modules\pengaturan\models\PengaturanMenu;

class RoleForm extends Model
{
    public $name;
    public $menu;

    public function rules()
    {
        return [
            [['name'], 'required', 'message'=> '{attribute} cannot be blank'],
            [['menu', 'name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'menu' => 'Menu',
        ];
    }
}