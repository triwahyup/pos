<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterGroupMaterial;
use app\modules\master\models\MasterGroupSupplier;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterSatuan;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_material_item".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $type_code
 * @property string|null $material_code
 * @property string|null $satuan_code
 * @property string|null $group_material_code
 * @property string|null $group_supplier_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property float|null $gram
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterMaterialItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_material_item';
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
            [['code', 'name', 'type_code', 'satuan_code', 'material_code'], 'required'],
            [['type_code', 'material_code', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3', 'harga_jual_1', 'harga_jual_2', 'harga_jual_3'], 'safe'],
            [['panjang', 'lebar', 'gram'], 'number'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 7],
            [['um_1', 'um_2', 'um_3'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 128],
            [['satuan_code', 'group_material_code', 'group_supplier_code'], 'string', 'max' => 3],
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
            'type_code' => 'Type',
            'material_code' => 'Material',
            'satuan_code' => 'Satuan',
            'group_material_code' => 'Group Material',
            'group_supplier_code' => 'Group Supplier',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'gram' => 'Gram',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode($type)
    {
        $model = MasterMaterialItem::find()->where(['type_code'=>$type])->count();
        $total=0;
        if($model > 0){
            $model = MasterMaterialItem::find()->where(['type_code'=>$type])->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -6);
        }
        if($type == \Yii::$app->params['MATERIAL_KERTAS_CODE']){
            return (string)'1'.sprintf('%06s', ($total+1));
        }else{
            return (string)'2'.sprintf('%06s', ($total+1));
        }
    }

    public function getMaterial()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'material_code']);
    }
    
    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public function getGroupMaterial()
    {
        return $this->hasOne(MasterGroupMaterial::className(), ['code' => 'group_material_code']);
    }

    public function getGroupSupplier()
    {
        return $this->hasOne(MasterGroupSupplier::className(), ['code' => 'group_supplier_code']);
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
}
