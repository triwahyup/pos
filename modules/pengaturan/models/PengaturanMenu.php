<?php

namespace app\modules\pengaturan\models;

use Yii;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pengaturan_menu".
 *
 * @property string $id
 * @property string|null $name
 * @property string|null $slug
 * @property int|null $level
 * @property string|null $link
 * @property string|null $icon
 * @property int|null $parent_id
 * @property int|null $urutan
 * @property int|null $position
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
            [['name', 'urutan', 'position'], 'required'],
            [['level', 'parent_1', 'parent_2', 'urutan', 'created_at', 'updated_at'], 'integer'],
            [['id', 'parent_id', 'position'], 'string', 'max' => 3],
            [['name', 'slug', 'link'], 'string', 'max' => 128],
            [['icon'], 'string', 'max' => 64],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'level' => 'Level',
            'link' => 'Link',
            'icon' => 'Icon',
            'parent_id' => 'Parent',
            'urutan' => 'Urutan',
            'position' => 'Position',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'parent_1' => 'Parent Menu 1',
            'parent_2' => 'Parent Menu 2',
        ];
    }

    public function newcode()
    {
        $model = PengaturanMenu::find()->count();
        $total=0;
        if($model > 0){
            $model = PengaturanMenu::find()->orderBy(['id'=>SORT_DESC])->one();
            $total = (int)substr($model->id, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function getKode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'position']);
    }

    public function getParent()
    {
        return $this->hasOne(PengaturanMenu::className(), ['id' => 'parent_id']);
    }

    public function getChild()
    {
        return $this->hasMany(PengaturanMenu::className(), ['parent_id' => 'id'])->orderBy(['urutan' => SORT_ASC]);;
    }
}