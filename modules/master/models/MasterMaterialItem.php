<?php

namespace app\modules\master\models;

use Yii;
use app\commands\Konstanta;
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
            [['type_code', 'material_code', 'harga_beli', 'harga_jual'], 'safe'],
            [['panjang', 'lebar', 'gram'], 'number'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 7],
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
            'satuan_code' => 'Satuan Code',
            'group_material_code' => 'Group Material',
            'group_supplier_code' => 'Group Supplier',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'gram' => 'Gram',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'harga_beli' => 'Harga Beli',
            'harga_jual' => 'Harga Jual',
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
        if($type == Konstanta::MATERIAL_KERTAS_CODE){
            return (string)'1'.sprintf('%06s', ($total+1));
        }else if($type == Konstanta::MATERIAL_TINTA_CODE){
            return (string)'2'.sprintf('%06s', ($total+1));
        }else{
            return (string)'3'.sprintf('%06s', ($total+1));
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
}
