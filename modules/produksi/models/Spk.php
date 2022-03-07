<?php

namespace app\modules\produksi\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterMesin;
use app\modules\master\models\MasterPerson;
use app\modules\sales\models\SalesOrderItem;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderProses;
use app\modules\produksi\models\SpkProduksi;
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
        return $this->hasMany(SalesOrderItem::className(), ['code' => 'no_so', 'type_code' => 'type_material']);
    }

    public function getItemsNonMaterial()
    {
        return SalesOrderItem::find()->where(['code' => $this->no_so])->andWhere('type_code <> "'.$this->type_material.'"')->all();
    }

    public function getItemMaterial()
    {
        return $this->hasOne(SalesOrderItem::className(), ['code' => 'no_so', 'type_code' => 'type_material']);
    }

    public function getPotongs()
    {
        return $this->hasMany(SalesOrderPotong::className(), ['code' => 'no_so']);
    }

    public function getProses()
    {
        return $this->hasMany(SalesOrderProses::className(), ['code' => 'no_so']);
    }

    public function getProduksiInAlls()
    {
        return $this->hasMany(SpkProduksi::className(), ['no_spk' => 'no_spk'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public $onStart = 1;
    public function getProduksiOnStarts()
    {
        return $this->hasMany(SpkProduksi::className(), ['no_spk' => 'no_spk', 'status_produksi' => 'onStart'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public $inProgress = 2;
    public function getProduksiInProgress()
    {
        return $this->hasMany(SpkProduksi::className(), ['no_spk' => 'no_spk', 'status_produksi' => 'inProgress'])
            ->orderBy(['no_spk'=>SORT_ASC, 'created_at' => SORT_ASC]);
    }

    public function itemPotong($no_so, $potong_id)
    {
        $model = SalesOrderPotong::findOne(['code'=>$no_so, 'urutan'=>$potong_id]);
        return $model;
    }

    public function setListColumn()
    {
        $model['operator'] = Profile::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.typeuser_code')
            ->where(['value' => \Yii::$app->params['TYPE_OPERATOR_PRODUKSI'], 'a.status'=>1])
            ->indexBy('user_id')
            ->column();
        $model['so_proses'] = SalesOrderProses::find()
            ->alias('a')
            ->select(['b.name'])
            ->leftJoin('master_proses b', 'b.code = a.proses_code')
            ->where(['b.status'=>1])
            ->indexBy('proses_code')
            ->column();
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
        $message = '';
        if($this->status_produksi==1){
            $message = '<span class="text-label text-default">Belum Proses</span>';
        }else if($this->status_produksi==2){
            $message = '<span class="text-label text-primary">In Progres</span>';
        }else if($this->status_produksi==3){
            $message = '<span class="text-label text-success">Done</span>';
        }
        return $message;
    }

    public function getUpProduksi()
    {
        $str = '<strong class="font-size-12">';
        $str .= (!empty($this->up_produksi || $this->up_produksi != 0)) ? $this->up_produksi.'%' : 0;
        $str .= '</strong>';
        $str .= '<span class="text-muted font-size-12">';
        
        if(!empty($this->up_produksi) || $this->up_produksi != 0){
            $stock = 0;
            $stockItem = $this->itemMaterial->inventoryStock;
            if(isset($stockItem)){
                $stock = $stockItem->satuanTerkecil($this->itemMaterial->item_code, [
                    0=>$this->itemMaterial->qty_order_1,
                    1=>$this->itemMaterial->qty_order_2
                ]);
            }
            $upproduksi = $stock * ($this->up_produksi/100);
    
            $str .= ' ('.number_format($upproduksi).' Lembar)';
            $str .= '</span>';
        }
        return $str;
    }
}
