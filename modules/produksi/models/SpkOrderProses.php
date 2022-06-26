<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterProses;
use app\modules\produksi\models\SpkOrderHistory;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_order_proses".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property int $proses_id
 * @property int $urutan
 * @property int|null $potong_id
 * @property string|null $uk_potong
 * @property string|null $proses_code
 * @property int|null $proses_type
 * @property string|null $mesin_type
 * @property float|null $qty_proses
 * @property float|null $qty_hasil
 * @property float|null $qty_rusak
 * @property float|null $gram
 * @property string|null $keterangan
 * @property int|null $status_produksi
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkOrderProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_order_proses';
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
            [['no_spk', 'item_code', 'proses_id'], 'required'],
            [['proses_id', 'urutan', 'potong_id', 'proses_type', 'status_produksi', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_proses', 'qty_hasil'], 'safe'],
            [['qty_rusak', 'gram'], 'number'],
            [['no_spk', 'uk_potong'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'mesin_type', 'supplier_code'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'item_code', 'supplier_code', 'proses_id'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'supplier_code', 'proses_id']],
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
            'potong_id' => 'Potong ID',
            'uk_potong' => 'Uk Potong',
            'proses_code' => 'Proses Code',
            'proses_type' => 'Proses Type',
            'mesin_type' => 'Mesin Type',
            'qty_proses' => 'Qty Proses',
            'qty_hasil' => 'Qty Hasil',
            'qty_rusak' => 'Qty Rusak',
            'gram' => 'Gram',
            'keterangan' => 'Keterangan',
            'status_produksi' => 'Status Produksi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->qty_proses = (!empty($this->qty_proses)) ? str_replace(',', '', $this->qty_proses) : null;
        $this->qty_hasil = (!empty($this->qty_hasil)) ? str_replace(',', '', $this->qty_hasil) : null;
        $this->qty_rusak = (!empty($this->qty_rusak)) ? str_replace(',', '', $this->qty_rusak) : null;
        return parent::beforeSave($attribute);
    }

    public function getSisa()
    {
        $total = 0;
        foreach($this->historys as $val){
            $total += $val->qty_proses;
        }
        $total = $this->qty_proses - $total;
        
        $desc = '';
        if($total > 0 && ($this->status_produksi == 1 || $this->status_produksi == 2)){
            $desc = '<span class="text-muted">Sisa: '.number_format($total).' LB</span>';
        }
        return ['sisa' => $total, 'desc' => $desc];
    }

    public function getCount()
    {
        return SpkOrderProses::find()->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code])->count();
    }

    public function getAlls()
    {
        return SpkOrderProses::find()->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code])->all();
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getitem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getProses()
    {
        return $this->hasOne(MasterProses::className(), ['code' => 'proses_code']);
    }

    public function getHistorys()
    {
        return $this->hasMany(SpkOrderHistory::className(), ['no_spk' => 'no_spk', 'item_code' => 'item_code', 'proses_id' => 'proses_id']);
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
