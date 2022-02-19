<?php

namespace app\modules\sales\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order_potong".
 *
 * @property string $code
 * @property string $item_code
 * @property int $urutan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $objek
 * @property float|null $total_objek
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrderPotong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order_potong';
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
            [['code', 'item_code', 'urutan'], 'required'],
            [['urutan', 'objek', 'status', 'created_at', 'updated_at'], 'integer'],
            [['panjang', 'lebar', 'total_objek'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['code', 'item_code', 'urutan'], 'unique', 'targetAttribute' => ['code', 'item_code', 'urutan']],
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
            'item_code' => 'Item Code',
            'urutan' => 'Urutan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'objek' => 'Objek',
            'total_objek' => 'Total Objek',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
