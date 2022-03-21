<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use app\modules\produksi\models\SpkPotongRollDetail;
use app\modules\produksi\models\TempSpkPotongRollDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_potong_roll".
 *
 * @property string $code
 * @property string $item_code
 * @property string|null $supplier_code
 * @property string|null $type_code
 * @property string|null $material_code
 * @property string|null $satuan_code
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkPotongRoll extends \yii\db\ActiveRecord
{
    public $item_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_potong_roll';
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
            [['date', 'item_name'], 'required'],
            [['date'], 'safe'],
            [['keterangan'], 'string'],
            [['post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code', 'type_code', 'material_code', 'satuan_code'], 'string', 'max' => 3],
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
            'item_code' => 'Item',
            'supplier_code' => 'Supplier',
            'type_code' => 'Type',
            'material_code' => 'Material',
            'satuan_code' => 'Satuan',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = SpkPotongRoll::find()->count();
        $total=0;
        if($model > 0){
            $model = SpkPotongRoll::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function beforeSave($attribute)
    {
        $this->date = date('Y-m-d', strtotime($this->date));
        return parent::beforeSave($attribute);
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
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

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getDetails()
    {
        return $this->hasMany(SpkPotongRollDetail::className(), ['code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempSpkPotongRollDetail::className(), ['code' => 'code']);
    }

    public function temps()
    {
        return TempSpkPotongRollDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getStatusPost()
    {
        $message = '';
        if($this->post == 1){
            $message = '<span class="text-label text-success">Sudah Post</span>';
        }else{
            $message = '<span class="text-label text-default">Belum Post</span>';
        }
        return $message;
    }
}
