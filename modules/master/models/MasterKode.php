<?php

namespace app\modules\master\models;

use Yii;
use app\models\AuthItemChild;
use app\modules\pengaturan\models\PengaturanMenu;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_kode".
 *
 * @property string $code
 * @property string|null $name
 * @property resource|null $type
 * @property string|null $value
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterKode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_kode';
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
            [['name', 'type'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 64],
            [['type', 'value'], 'string', 'max' => 32],
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
            'type' => 'Type',
            'value' => 'Value',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterKode::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterKode::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -3);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getMenu()
    {
        $this->value = str_replace(' ', '-', $this->value);
		return AuthItemChild::find()->where(['parent'=>$this->value])->all();
    }

    public function getAuthItem()
    {
        $this->value = str_replace(' ', '-', $this->value);
        $model = AuthItemChild::find()->where(['parent'=>$this->value])->all();
        $data = [];
        foreach($model as $val){
			$menu = PengaturanMenu::findOne(['slug'=>$val->child]);
            if(isset($menu->link)){
                if($menu->link != "#"){
                    $data[] = $menu->name;
                }
            }
		}
        return $data;
    }
}