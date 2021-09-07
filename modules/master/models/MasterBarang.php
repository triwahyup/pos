<?php

namespace app\modules\master\models;

use Yii;
use app\commands\Konstanta;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterJenisBarang;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_barang".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $type
 * @property string|null $jenis
 * @property float|null $panjang
 * @property float|null $lebar
 * @property float|null $gram
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
            [['name', 'type', 'jenis'], 'required'],
            [['keterangan'], 'safe'],
            [['panjang', 'lebar', 'gram'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'type', 'jenis'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 128],
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
            'type' => 'Type',
            'jenis' => 'Jenis',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'gram' => 'Gram',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'keterangan' => 'Keterangan',
        ];
    }

    public function newcode($type)
    {
        $model = MasterBarang::find()->where(['type'=>$type])->count();
        $total=0;
        if($model > 0){
            $model = MasterBarang::find()
                ->where(['type'=>$type])
                ->orderBy(['code'=>SORT_DESC])
                ->one();
            $total = (int)substr($model->code, -3);
        }
        if($type == Konstanta::TYPE_KERTAS){
            return (string)'KRTS-'.sprintf('%03s', ($total+1));
        }
        else if($type == Konstanta::TYPE_TINTA){
            return (string)'TINT-'.sprintf('%03s', ($total+1));
        }
        else if($type == Konstanta::TYPE_LAIN){
            return (string)'LAIN-'.sprintf('%03s', ($total+1));
        }
    }

    public function getTypeBarang()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type']);
    }

    public function getJenisBarang()
    {
        return $this->hasOne(MasterJenisBarang::className(), ['code' => 'jenis']);
    }
}