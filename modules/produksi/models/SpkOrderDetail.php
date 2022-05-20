<?php

namespace app\modules\produksi\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_order_detail".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property int $urutan
 * @property string|null $tgl_spk
 * @property string $proses_code
 * @property int|null $proses_type
 * @property string|null $outsource_code
 * @property string|null $mesin_code
 * @property string|null $mesin_type
 * @property string|null $uk_potong
 * @property string|null $no_sj
 * @property string|null $nopol
 * @property float|null $qty_hasil
 * @property float|null $qty_rusak
 * @property float|null $qty_proses
 * @property float|null $gram
 * @property int|null $user_id
 * @property string|null $keterangan
 * @property int|null $status_produksi
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkOrderDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_order_detail';
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
            [['no_spk', 'item_code', 'urutan', 'proses_code'], 'required'],
            [['urutan', 'urutan_proses', 'proses_type', 'potong_id', 'user_id', 'status_produksi', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tgl_spk'], 'safe'],
            [['qty_hasil', 'qty_rusak', 'qty_proses', 'gram'], 'number'],
            [['no_spk', 'uk_potong', 'no_sj', 'nopol'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'outsource_code', 'mesin_code', 'mesin_type'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'item_code', 'urutan'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'urutan']],
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
            'item_code' => 'Item Code',
            'urutan' => 'Urutan',
            'tgl_spk' => 'Tgl Spk',
            'proses_code' => 'Proses Code',
            'proses_type' => 'Proses Type',
            'outsource_code' => 'Outsource Code',
            'mesin_code' => 'Mesin Code',
            'mesin_type' => 'Mesin Type',
            'uk_potong' => 'Uk Potong',
            'no_sj' => 'No Sj',
            'nopol' => 'Nopol',
            'qty_hasil' => 'Qty Hasil',
            'qty_rusak' => 'Qty Rusak',
            'qty_proses' => 'Qty Proses',
            'gram' => 'Gram',
            'user_id' => 'User ID',
            'keterangan' => 'Keterangan',
            'status_produksi' => 'Status Produksi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->tgl_spk = (!empty($this->tgl_spk)) ? date('Y-m-d', strtotime($this->tgl_spk)) : null;
        $this->qty_proses = (!empty($this->qty_proses)) ? str_replace(',', '', $this->qty_proses) : null;
        $this->qty_hasil = (!empty($this->qty_hasil)) ? str_replace(',', '', $this->qty_hasil) : null;
        $this->qty_rusak = (!empty($this->qty_rusak)) ? str_replace(',', '', $this->qty_rusak) : null;
        return parent::beforeSave($attribute);
    }

    public function getQtyProses()
    {
        $models = SpkOrderDetail::find()
            ->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code, 'proses_code'=>$this->proses_code])
            ->all();
        $total = 0;
        foreach($models as $val){
            if($val->qty_hasil != 0 || $val->qty_hasil != null){
                $total += $val->qty_hasil;
            }else{
                $total += $val->qty_proses;
            }
        }
        return $total;
    }

    public function getCount()
    {
        return SpkOrderDetail::find()->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code])->count();
    }

    public function getAlls()
    {
        return SpkOrderDetail::find()->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code])->all();
    }

    public function getitem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getMesin()
    {
        return $this->hasOne(MasterMesin::className(), ['code' => 'mesin_code']);
    }

    public function getProses()
    {
        return $this->hasOne(MasterProses::className(), ['code' => 'proses_code']);
    }

    public function getOperator()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getOutsource()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'outsource_code']);
    }

    public function getStatusProduksi()
    {
        $message = '';
        if($this->status_produksi==1){
            $message = '<span class="text-label text-default">On Start</span>';
        }else if($this->status_produksi==2){
            $message = '<span class="text-label text-primary">In Progres</span>';
        }else if($this->status_produksi==3){
            $message = '<span class="text-label text-success">Done</span>';
        }else if($this->status_produksi==4){
            $message = '<span class="text-label text-warning">Done Sebagian</span>';
        }else if($this->status_produksi==5){
            $message = '<span class="text-label text-danger">Rusak Sebagian</span>';
        }else{
            $message = '<span class="text-label text-warning">Belum Proses</span>';
        }
        return $message;
    }
}
