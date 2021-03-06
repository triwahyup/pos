<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;

/**
 * This is the model class for table "temp_master_material_pricelist".
 *
 * @property int $id
 * @property string $item_code
 * @property int $urutan
 * @property string|null $name
 * @property string|null $um_1
 * @property string|null $um_2
 * @property string|null $um_3
 * @property float|null $harga_beli_1
 * @property float|null $harga_beli_2
 * @property float|null $harga_beli_3
 * @property float|null $harga_jual_1
 * @property float|null $harga_jual_2
 * @property float|null $harga_jual_3
 * @property int|null $status_active
 * @property int|null $user_id
 */
class TempMasterMaterialPricelist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_master_material_pricelist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['urutan', 'status_active', 'user_id'], 'integer'],
            [['harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3'], 'safe'],
            [['supplier_code'], 'string', 'max' => 3],
            [['item_code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 64],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_code' => 'Item Code',
            'urutan' => 'Urutan',
            'name' => 'Name',
            'supplier_code' => 'Supplier',
            'um_1' => 'Um 1',
            'um_2' => 'Um 2',
            'um_3' => 'Um 3',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'harga_jual_1' => 'Harga Jual 1',
            'harga_jual_2' => 'Harga Jual 2',
            'harga_jual_3' => 'Harga Jual 3',
            'status_active' => 'Status Active',
            'user_id' => 'User ID',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->harga_beli_1 = str_replace(',', '', $this->harga_beli_1);
        $this->harga_beli_2 = str_replace(',', '', $this->harga_beli_2);
        $this->harga_beli_3 = str_replace(',', '', $this->harga_beli_3);
        $this->harga_jual_1 = str_replace(',', '', $this->harga_jual_1);
        $this->harga_jual_2 = str_replace(',', '', $this->harga_jual_2);
        $this->harga_jual_3 = str_replace(',', '', $this->harga_jual_3);
        return parent::beforeSave($attribute);
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

    public function getCount()
    {
        return TempMasterMaterialPricelist::find()->where(['supplier_code'=>$this->supplier_code, 'user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempMasterMaterialPricelist::find()->where(['supplier_code'=>$this->supplier_code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }
}
