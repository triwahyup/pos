<?php

namespace app\modules\master\models;

use Yii;
use app\commands\Konstanta;
use app\modules\master\models\MasterKode;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_jenis_barang".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $type
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterJenisBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_jenis_barang';
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
            [['name', 'type'], 'required'],
            [['keterangan'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['code', 'type'], 'string', 'max' => 8],
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
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function newcode($type)
    {
        $model = MasterJenisBarang::find()->where(['type'=>$type])->count();
        $total=0;
        if($model > 0){
            $model = MasterJenisBarang::find()
                ->where(['type'=>$type])
                ->orderBy(['code'=>SORT_DESC])
                ->one();
            $total = (int)substr($model->code, -3);
        }
        if($type == Konstanta::TYPE_KERTAS){
            return (string)'JK-'.sprintf('%03s', ($total+1));
        }
        else if($type == Konstanta::TYPE_TINTA){
            return (string)'JT-'.sprintf('%03s', ($total+1));
        }
        else if($type == Konstanta::TYPE_LAIN){
            return (string)'JL-'.sprintf('%03s', ($total+1));
        }
    }

    public function getTypeBarang()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type']);
    }
}