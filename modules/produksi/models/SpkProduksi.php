<?php

namespace app\modules\produksi\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterProses;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_produksi".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property string|null $proses_code
 * @property int|null $proses_type
 * @property string|null $mesin_code
 * @property string|null $mesin_type
 * @property float|null $qty_hasil
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkProduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_produksi';
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
            [['no_spk', 'item_code', 'proses_code', 'mesin_code', 'mesin_type', 'qty_proses', 'user_id', 'uk_potong'], 'required'],
            [['status_produksi', 'potong_id', 'urutan', 'proses_type', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['qty_hasil', 'qty_rusak', 'qty_proses', 'tgl_spk', 'gram'], 'safe'],
            [['no_spk', 'uk_potong'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'mesin_code', 'mesin_type'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'item_code', 'potong_id', 'urutan'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'potong_id', 'urutan']],
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
            'proses_code' => 'Proses Code',
            'proses_type' => 'Proses Type',
            'mesin_code' => 'Mesin Code',
            'mesin_type' => 'Mesin Type',
            'qty_hasil' => 'Qty Hasil',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->tgl_spk = date('Y-m-d', strtotime($this->tgl_spk));
        $this->qty_proses = str_replace(',', '', $this->qty_proses);
        $this->qty_hasil = str_replace(',', '', $this->qty_hasil);
        $this->qty_rusak = str_replace(',', '', $this->qty_rusak);
        return parent::beforeSave($attribute);
    }

    public function getQtyProses()
    {
        $models = SpkProduksi::find()
            ->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code, 'potong_id'=>$this->potong_id, 'proses_code'=>$this->proses_code])
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
        return SpkProduksi::find()->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code, 'potong_id'=>$this->potong_id])->count();
    }

    public function getAlls()
    {
        return SpkProduksi::find()->where(['no_spk'=>$this->no_spk, 'item_code'=>$this->item_code, 'potong_id'=>$this->potong_id])->all();
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
            $message = '<span class="text-label text-danger">Rusak Sebagian</span>';
        }
        return $message;
    }
}