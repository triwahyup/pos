<?php

namespace app\modules\pengaturan\models;

use Yii;

/**
 * This is the model class for table "pengaturan_menu_favorite".
 *
 * @property int $id
 * @property int|null $menu_id
 * @property int|null $size
 * @property int|null $open
 * @property string|null $type
 * @property int|null $user_id
 */
class PengaturanMenuFavorite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengaturan_menu_favorite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'menu_id', 'size', 'open', 'user_id'], 'integer'],
            [['type'], 'string', 'max' => 16],
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
            'menu_id' => 'Menu ID',
            'size' => 'Size',
            'open' => 'Open',
            'type' => 'Type',
            'user_id' => 'User ID',
        ];
    }
}
