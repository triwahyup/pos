<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterialPricelist;
use app\modules\master\models\MasterSatuan;
use app\modules\master\models\TempMasterMaterialPricelist;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_material".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $type_code
 * @property string|null $material_code
 * @property string|null $satuan_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property float|null $gram
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterMaterial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_material';
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
            [['panjang', 'lebar', 'gram'], 'number'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['type_code', 'material_code', 'satuan_code'], 'string', 'max' => 3],
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
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'gram' => 'Gram',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function generateCode($type)
    {
        $model = MasterMaterial::find()->where(['type_code'=>$type])->count();
        $total=0;
        if($model > 0){
            $model = MasterMaterial::find()->where(['type_code'=>$type])->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -6);
        }
        $kode = MasterKode::findOne(['code'=>$type]);
        if($kode->value == \Yii::$app->params['TYPE_KERTAS']){
            return (string)'1'.sprintf('%06s', ($total+1));
        }else{
            return (string)'2'.sprintf('%06s', ($total+1));
        }
    }

    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getMaterial()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'material_code']);
    }

    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public $status_active=1;
    public function getPricelist()
    {
        return $this->hasOne(MasterMaterialPricelist::className(), ['item_code' => 'code', 'status_active' => 'status_active']);
    }

    public function getPricelists()
    {
        return $this->hasMany(MasterMaterialPricelist::className(), ['item_code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempMasterMaterialPricelist::className(), ['item_code' => 'code']);
    }

    public function temps()
    {
        return TempMasterMaterialPricelist::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }
}
