<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterBarangPricelist;
use app\modules\master\models\MasterSatuan;
use app\modules\master\models\TempMasterBarangPricelist;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_barang".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $satuan_code
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_barang';
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
            [['code', 'name', 'satuan_code'], 'required'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 7],
            [['name'], 'string', 'max' => 128],
            [['satuan_code', 'type_code'], 'string', 'max' => 3],
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
            'satuan_code' => 'Satuan',
            'type_code' => 'Type',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function generateCode($type)
    {
        $model = MasterBarang::find()->where(['type_code'=>$type])->count();
        $total=0;
        if($model > 0){
            $model = MasterBarang::find()->where(['type_code'=>$type])->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -5);
        }
        $kode = MasterKode::findOne(['code'=>$type]);
        if($kode->value == \Yii::$app->params['TYPE_PLATE']){
            return (string)'PL'.sprintf('%05s', ($total+1));
        }else if($kode->value == \Yii::$app->params['TYPE_PISAU']){
            return (string)'PS'.sprintf('%05s', ($total+1));
        }else if($kode->value == \Yii::$app->params['TYPE_INVENTARIS']){
            return (string)'IN'.sprintf('%05s', ($total+1));
        }else{
            return 'LN'.(string)sprintf('%05s', ($total+1));
        }
    }

    public function getTypeCode()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_code']);
    }

    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public $status_active=1;
    public function getPricelist()
    {
        return $this->hasOne(MasterBarangPricelist::className(), ['barang_code' => 'code', 'status_active' => 'status_active']);
    }

    public function getPricelists()
    {
        return $this->hasMany(MasterBarangPricelist::className(), ['barang_code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempMasterBarangPricelist::className(), ['barang_code' => 'code']);
    }

    public function temps()
    {
        return TempMasterBarangPricelist::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }
}
