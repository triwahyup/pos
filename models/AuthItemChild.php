<?php

namespace app\models;

use Yii;
use app\modules\pengaturan\models\PengaturanMenu;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 */
class AuthItemChild extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }
}
