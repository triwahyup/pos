<?php

namespace app\modules\purchasing\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_internal_detail".
 *
 * @property string $no_pi
 * @property int $urutan
 * @property string|null $item_name
 * @property float|null $qty
 * @property float|null $harga_beli
 * @property float|null $total_order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseInternalDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_internal_detail';
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
            [['no_pi', 'urutan'], 'required'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty', 'harga_beli', 'total_order'], 'number'],
            [['um'], 'string', 'max' => 16],
            [['no_pi'], 'string', 'max' => 12],
            [['item_name'], 'string', 'max' => 128],
            [['no_pi', 'urutan'], 'unique', 'targetAttribute' => ['no_pi', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_pi' => 'No Pi',
            'urutan' => 'Urutan',
            'item_name' => 'Item Name',
            'qty' => 'Qty',
            'harga_beli' => 'Harga Beli',
            'total_order' => 'Total Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
