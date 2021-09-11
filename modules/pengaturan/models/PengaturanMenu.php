<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pengaturan_menu".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $slug
 * @property string|null $link
 * @property string|null $icon
 * @property string|null $parent_code
 * @property string|null $type_code
 * @property int|null $level
 * @property int|null $urutan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PengaturanMenu extends \yii\db\ActiveRecord
{
    public $parent_1;
    public $parent_2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengaturan_menu';
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
            [['name', 'urutan'], 'required'],
            [['parent_1', 'parent_2'], 'safe'],
            [['level', 'urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'parent_code', 'type_code'], 'string', 'max' => 3],
            [['name', 'slug', 'link'], 'string', 'max' => 128],
            [['icon'], 'string', 'max' => 64],
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
            'slug' => 'Slug',
            'link' => 'Link',
            'icon' => 'Icon',
            'parent_code' => 'Parent',
            'type_code' => 'Position',
            'level' => 'Level',
            'urutan' => 'Urutan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = PengaturanMenu::find()->count();
        $total=0;
        if($model > 0){
            $model = PengaturanMenu::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getTypeKode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getParent()
    {
        return $this->hasOne(PengaturanMenu::className(), ['code' => 'parent_code']);
    }

    public function getChild()
    {
        return $this->hasMany(PengaturanMenu::className(), ['parent_code' => 'code'])->orderBy(['urutan' => SORT_ASC]);;
    }
}
