<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterPerson;
use app\modules\produksi\models\SpkItem;
use app\modules\produksi\models\SpkPotong;
use app\modules\produksi\models\SpkProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk".
 *
 * @property string $no_spk
 * @property string|null $tgl_spk
 * @property string|null $no_so
 * @property string|null $tgl_so
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $status_produksi
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Spk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk';
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
            [['no_spk'], 'required'],
            [['tgl_spk', 'tgl_so', 'deadline', 'type_order', 'up_produksi'], 'safe'],
            [['ekspedisi_flag', 'term_in', 'status', 'status_produksi', 'created_at', 'updated_at'], 'integer'],
            [['customer_code'], 'string', 'max' => 3],
            [['ekspedisi_code'], 'string', 'max' => 7],
            [['no_spk', 'no_so'], 'string', 'max' => 12],
            [['name', 'keterangan'], 'string', 'max' => 128],
            [['no_spk'], 'unique'],
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
            'tgl_spk' => 'Tgl Spk',
            'no_so' => 'No So',
            'tgl_so' => 'Tgl So',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'status_produksi' => 'Status Produksi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = Spk::find()->count();
        $total=0;
        if($model > 0){
            $model = Spk::find()->orderBy(['no_spk'=>SORT_DESC])->one();
            $total = (int)substr($model->no_spk, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getCustomer()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'customer_code']);
    }

    public function getEkspedisi()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'ekspedisi_code']);
    }

    public $type_material = '007';
    public function getItemsMaterial()
    {
        return $this->hasMany(SpkItem::className(), ['no_spk' => 'no_spk', 'type_code' => 'type_material']);
    }

    public function getItemsNonMaterial()
    {
        return SpkItem::find()->where(['no_spk' => $this->no_spk])->andWhere('type_code <> "'.$this->type_material.'"')->all();
    }

    public function getPotongs()
    {
        return $this->hasMany(SpkPotong::className(), ['code' => 'code']);
    }

    public function getProses()
    {
        return $this->hasMany(SpkProses::className(), ['code' => 'code']);
    }

    public function getTypeOrder()
    {
        $message = '';
        if($this->type_order == 1){
            $message = 'Produksi';
        }else{
            $message = 'Jasa';
        }
        return $message;
    }
}
