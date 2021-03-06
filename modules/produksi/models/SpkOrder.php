<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use app\modules\produksi\models\SpkOrderProses;
use app\modules\produksi\models\SpkOrderHistory;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_order".
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
class SpkOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_order';
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
            [['tgl_spk', 'tgl_so', 'deadline', 'type_order', 'up_produksi', 'total_qty', 'total_qty_up'], 'safe'],
            [['sales_code', 'ekspedisi_flag', 'term_in', 'type_qty', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'total_warna', 'status', 'status_produksi', 'created_at', 'updated_at'], 'integer'],
            [['customer_code', 'ekspedisi_code', 'satuan_ikat_code'], 'string', 'max' => 3],
            [['nick_name'], 'string', 'max' => 4],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['no_spk', 'no_so'], 'string', 'max' => 12],
            [['repeat_code'], 'string', 'max' => 16],
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
            'name' => 'Nama Job',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'status_produksi' => 'Status Produksi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = SpkOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = SpkOrder::find()->orderBy(['no_spk'=>SORT_DESC])->one();
            $total = (int)substr($model->no_spk, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getSales()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'sales_code']);
    }

    public function getCustomer()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'customer_code']);
    }

    public function getEkspedisi()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'ekspedisi_code']);
    }

    public function getSatuan()
    {
        return $this->hasOne(MasterSatuan::className(), ['code' => 'satuan_code']);
    }

    public function getHistorys()
    {
        return $this->hasMany(SpkOrderHistory::className(), ['no_spk' => 'no_spk'])
            ->orderBy(['no_spk'=>SORT_ASC, 'urutan' => SORT_ASC]);
    }

    public function getProduksiInAlls()
    {
        $models = SpkOrderProses::find()
            ->where(['no_spk' => $this->no_spk])
            ->orderBy(['no_spk'=>SORT_ASC, 'proses_id' => SORT_ASC])
            ->all();
        $results = [];
        foreach($models as $val){
            $results[$val->supplier->name][] = [
                'attributes' => $val->attributes,
                'proses_name' => $val->proses->name,
                'status_produksi' => $val->statusProduksi,
                'sisa' => $val->sisa['desc'],
            ];
        }
        return $results;
    }

    public $onStart = 1;
    public function getProduksiOnStarts()
    {
        return $this->hasMany(SpkOrderProses::className(), ['no_spk' => 'no_spk', 'status_produksi' => 'onStart'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public $inProgress = 2;
    public function getProduksiInProgress()
    {
        return $this->hasMany(SpkOrderProses::className(), ['no_spk' => 'no_spk', 'status_produksi' => 'inProgress'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public function getHistoryOnStarts()
    {
        return $this->hasMany(SpkOrderHistory::className(), ['no_spk' => 'no_spk', 'status_produksi' => 'onStart'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public function getHistoryInProgress()
    {
        return $this->hasMany(SpkOrderHistory::className(), ['no_spk' => 'no_spk', 'status_produksi' => 'inProgress'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public function getProduksiIsNull()
    {
        $model = SpkOrderProses::find()->where(['no_spk'=>$this->no_spk])->andWhere('status_produksi is null')->count();
        return $model;
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

    public function getStatusProduksi()
    {
        $hist = SpkOrderHistory::find()
            ->alias('a')
            ->select(['b.name'])
            ->leftJoin('master_proses b', 'b.code = a.proses_code')
            ->where(['status_produksi'=>2])
            ->orderBy(['a.urutan'=>SORT_DESC])
            ->asArray()
            ->limit(1)
            ->one();
        $message = '';
        if($this->status_produksi==1){
            $message = '<span class="text-label text-default">Belum Proses</span>';
        }else if($this->status_produksi==2){
            if(!empty($hist['name'])){
                $message = '<span class="text-label text-primary">Proses '.$hist['name'].'</span>';
            }else{
                $message = '<span class="text-label text-primary">Tidak ada proses</span>';
            }
        }else if($this->status_produksi==3){
            $message = '<span class="text-label text-warning">Finish</span>';
        }else if($this->status_produksi==4){
            $message = '<span class="text-label text-success">Closing</span>';
        }
        return $message;
    }
}
