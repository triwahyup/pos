<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_material_item_pricelist".
 *
 * @property string $item_code
 * @property int $urutan
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property int|null $status
 * @property int|null $status_active
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterMaterialItemPricelist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_material_item_pricelist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_code', 'urutan'], 'required'],
            [['urutan', 'status', 'status_active', 'created_at', 'updated_at'], 'integer'],
            [['harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3'], 'number'],
            [['item_code'], 'string', 'max' => 7],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 64],
            [['item_code', 'urutan'], 'unique', 'targetAttribute' => ['item_code', 'urutan']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_code' => 'Item Code',
            'urutan' => 'Urutan',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'status' => 'Status',
            'status_active' => 'Status Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getStatusActive()
    {
        $message = '';
        if($this->status_active == 1){
            $message = '<span class="text-label text-success">Pricelist Active</span>';
        }else{
            $message = '<span class="text-label text-default">Not Active</span>';
        }
        return $message;
    }
}