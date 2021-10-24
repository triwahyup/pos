<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMaterialItem;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_detail_bahan".
 *
 * @property string $no_spk
 * @property int $urutan
 * @property string $order_code
 * @property string $item_code
 * @property string|null $item_bahan_code
 * @property string|null $satuan_bahan_code
 * @property string|null $type_bahan_code
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property float|null $qty_1
 * @property float|null $qty_2
 * @property float|null $qty_3
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkDetailBahan extends \yii\db\ActiveRecord
{
    public $item_name;
    public $type_bahan;
    public $tgl_spk;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_detail_bahan';
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
            [['item_code', 'item_bahan_code'], 'required'],
            [['tgl_spk'], 'safe'],
            [['urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3', 'qty_1', 'qty_2', 'qty_3'], 'number'],
            [['item_bahan_name'], 'string', 'max' => 128],
            [['no_spk'], 'string', 'max' => 12],
            [['order_code', 'satuan_bahan_code', 'type_bahan_code'], 'string', 'max' => 3],
            [['item_code', 'item_bahan_code'], 'string', 'max' => 7],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['no_spk', 'urutan', 'order_code', 'item_code'], 'unique', 'targetAttribute' => ['no_spk', 'urutan', 'order_code', 'item_code']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_spk' => 'No Spk',
            'urutan' => 'Urutan',
            'order_code' => 'Order Code',
            'item_code' => 'Item Code',
            'item_bahan_code' => 'Item Bahan Code',
            'satuan_bahan_code' => 'Satuan Bahan Code',
            'type_bahan_code' => 'Type Bahan Code',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'qty_1' => 'Qty 1',
            'qty_2' => 'Qty 2',
            'qty_3' => 'Qty 3',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_code']);
    }

    public function getItemBahan()
    {
        return $this->hasOne(MasterMaterialItem::className(), ['code' => 'item_bahan_code']);
    }

    public function getTypeBahan()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'type_bahan_code']);
    }

    public function getDatas()
    {
        return SpkDetailBahan::find()->where(['no_spk'=> $this->no_spk, 'item_code' => $this->item_code])->all();
    }
}
