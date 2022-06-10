<?php

namespace app\modules\produksi\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_order_proses_history".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property int $proses_id
 * @property int $urutan
 * @property string|null $tgl_spk
 * @property string|null $tgl_history
 * @property int|null $potong_id
 * @property string|null $uk_potong
 * @property string|null $proses_code
 * @property int|null $proses_type
 * @property string|null $outsource_code
 * @property string|null $mesin_code
 * @property string|null $mesin_type
 * @property string|null $no_sj
 * @property string|null $nopol
 * @property float|null $qty_proses
 * @property float|null $qty_hasil
 * @property float|null $qty_rusak
 * @property float|null $gram
 * @property int|null $user_id
 * @property string|null $keterangan
 * @property int|null $status_produksi
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkOrderHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_order_history';
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
            [['proses_id', 'urutan', 'potong_id', 'proses_type', 'user_id', 'status_produksi', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tgl_spk', 'tgl_history', 'qty_proses', 'qty_hasil', 'qty_rusak', 'gram'], 'safe'],
            [['no_spk', 'uk_potong', 'no_sj', 'nopol'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'outsource_code', 'mesin_code', 'mesin_type'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'item_code', 'proses_id', 'urutan'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'proses_id', 'urutan']],
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
            'proses_id' => 'Proses ID',
            'urutan' => 'Urutan',
            'tgl_spk' => 'Tgl Spk',
            'tgl_history' => 'Tgl History',
            'potong_id' => 'Potong ID',
            'uk_potong' => 'Uk Potong',
            'proses_code' => 'Proses Code',
            'proses_type' => 'Proses Type',
            'outsource_code' => 'Outsource Code',
            'mesin_code' => 'Mesin Code',
            'mesin_type' => 'Mesin Type',
            'no_sj' => 'No Sj',
            'nopol' => 'Nopol',
            'qty_proses' => 'Qty Proses',
            'qty_hasil' => 'Qty Hasil',
            'qty_rusak' => 'Qty Rusak',
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
        $this->tgl_spk = date('Y-m-d', strtotime($this->tgl_spk));
        $this->tgl_history = date('Y-m-d');
        $this->qty_proses = str_replace(',', '', $this->qty_proses);
        $this->qty_hasil = str_replace(',', '', $this->qty_hasil);
        $this->qty_rusak = str_replace(',', '', $this->qty_rusak);
        return parent::beforeSave($attribute);
    }

    public function getOutsource()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'outsource_code']);
    }

    public function getMesin()
    {
        return $this->hasOne(MasterMesin::className(), ['code' => 'mesin_code']);
    }

    public function getOperator()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getProses()
    {
        return $this->hasOne(MasterProses::className(), ['code' => 'proses_code']);
    }

    public function getCount()
    {
        return SpkOrderHistory::find()->where(['no_spk' => $this->no_spk, 'item_code' => $this->item_code])->count();
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

    public function getSet_status_produksi()
    {
        $result = str_replace(',', '', $this->qty_proses) - str_replace(',', '', $this->qty_hasil);
        if($result == 0){
            $this->status_produksi = 3;
        }else{
            if($this->qty_rusak > 0){
                $this->status_produksi = 5;
            }else{
                if($this->qty_hasil > 0){
                    $this->status_produksi = 4;
                }else{
                    $this->status_produksi = 2;
                }
            }
        }
        return $this->status_produksi;
    }

    public function getSisa()
    {
        $total = $this->qty_proses - $this->qty_hasil - $this->qty_rusak;
        $desc = '';
        if($total > 0 && $this->status_produksi != 1){
            $desc = '<span class="font-size-10 text-muted">Sisa: '.number_format($total).' LB</span>';
        }
        return $desc;
    }
}
