<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\sales\models\SalesOrder;
use app\modules\sales\models\SpkDetail;
use app\modules\sales\models\SpkDetailProduksi;
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
            [['tgl_spk', 'tgl_so'], 'safe'],
            [['status', 'status_produksi', 'created_at', 'updated_at'], 'integer'],
            [['no_spk', 'no_so'], 'string', 'max' => 12],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk'], 'unique'],
            [['status', 'status_produksi'], 'default', 'value' => 1],
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

    public function getSales()
    {
        return $this->hasOne(SpkDetail::className(), ['no_so' => 'no_so']);
    }
    
    public function getDetails()
    {
        return $this->hasMany(SpkDetail::className(), ['no_spk' => 'no_spk']);
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(SpkDetailProduksi::className(), ['no_spk' => 'no_spk']);
    }
}
