<?php

namespace app\modules\sales\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\master\models\MasterPerson;
use app\modules\sales\models\SalesOrderItem;
use app\modules\sales\models\SalesOrderPotong;
use app\modules\sales\models\SalesOrderProses;
use app\modules\sales\models\TempSalesOrderItem;
use app\modules\sales\models\TempSalesOrderPotong;
use app\modules\sales\models\TempSalesOrderProses;

/**
 * This is the model class for table "sales_order".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $tgl_so
 * @property string|null $no_po
 * @property string|null $tgl_po
 * @property string|null $customer_code
 * @property int|null $type_order
 * @property int|null $up_produksi
 * @property string|null $ekspedisi_name
 * @property float|null $ppn
 * @property float|null $total_order_material
 * @property float|null $total_biaya_produksi
 * @property float|null $grand_total
 * @property string|null $keterangan
 * @property int|null $post
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales_order';
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
            [['name', 'type_order', 'customer_code', 'no_po', 'ekspedisi_flag'], 'required'],
            [['tgl_so', 'tgl_po', 'deadline'], 'safe'],
            [['term_in', 'ekspedisi_flag', 'type_order', 'up_produksi', 'post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ppn', 'total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_ppn', 'grand_total'], 'number'],
            [['code', 'no_po'], 'string', 'max' => 12],
            [['name', 'ekspedisi_name', 'keterangan'], 'string', 'max' => 128],
            [['customer_code'], 'string', 'max' => 3],
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
            'name' => 'Nama Job',
            'tgl_so' => 'Tgl So',
            'no_po' => 'No Po',
            'tgl_po' => 'Tgl Po',
            'customer_code' => 'Customer',
            'term_in' => 'Term In',
            'type_order' => 'Type Order',
            'up_produksi' => 'Up Produksi',
            'ekspedisi_flag' => 'Pengambilan Barang',
            'ekspedisi_name' => 'Ekspedisi Name',
            'ppn' => 'Ppn',
            'total_order_material' => 'Total Order',
            'total_biaya_produksi' => 'Total Biaya Produksi',
            'grand_total' => 'Grand Total',
            'keterangan' => 'Keterangan',
            'post' => 'Post',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = SalesOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = SalesOrder::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function beforeSave($attribute)
    {
        $this->tgl_so = date('Y-m-d', strtotime($this->tgl_so));
        $this->tgl_po = date('Y-m-d', strtotime($this->tgl_po));
        $this->deadline = date('Y-m-d', strtotime($this->deadline));
        return parent::beforeSave($attribute);
    }

    public function getCustomer()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'customer_code']);
    }

    public $type_material = '007';
    public function getItemsMaterial()
    {
        return $this->hasMany(SalesOrderItem::className(), ['code' => 'code', 'type_code' => 'type_material']);
    }

    public function getItemsNonMaterial()
    {
        return SalesOrderItem::find()->where(['code' => $this->code])->andWhere('type_code <> "'.$this->type_material.'"')->all();
    }

    public function getItems()
    {
        return $this->hasMany(SalesOrderItem::className(), ['code' => 'code']);
    }

    public function getPotongs()
    {
        return $this->hasMany(SalesOrderPotong::className(), ['code' => 'code']);
    }

    public function getProses()
    {
        return $this->hasMany(SalesOrderProses::className(), ['code' => 'code']);
    }

    public function getPotongTemps()
    {
        return $this->hasMany(TempSalesOrderPotong::className(), ['code' => 'code']);
    }

    public function getItemTemps()
    {
        return $this->hasMany(TempSalesOrderItem::className(), ['code' => 'code']);
    }

    public function getProsesTemps()
    {
        return $this->hasMany(TempSalesOrderProses::className(), ['code' => 'code']);
    }

    public function potongTemps()
    {
        return TempSalesOrderPotong::findAll(['user_id' => \Yii::$app->user->id]);
    }

    public function itemTemps()
    {
        return TempSalesOrderItem::findAll(['user_id' => \Yii::$app->user->id]);
    }

    public function prosesTemps()
    {
        return TempSalesOrderProses::findAll(['user_id' => \Yii::$app->user->id]);
    }

    public function itemsMaterialTemps()
    {
        return TempSalesOrderItem::findAll(['type_code'=>$this->type_material, 'user_id' => \Yii::$app->user->id]);
    }

    public function itemsNonMaterialTemps()
    {
        return TempSalesOrderItem::find()->where('type_code <> "'.$this->type_material.'"')->all();
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
    
    public function getStatusPost()
    {
        $message = '';
        if($this->post == 1){
            $message = '<span class="text-label text-success">Sudah Post</span>';
        }else{
            $message = '<span class="text-label text-default">Belum Post</span>';
        }
        return $message;
    }

    public function getTotalOrder()
    {
        $total_order_material=0;
        foreach($this->itemsMaterialTemps() as $val){
            $total_order_material += $val->total_order;
        }
        $this->total_order_material = $total_order_material;
        $total_order_bahan=0;
        foreach($this->itemsNonMaterialTemps() as $val){
            $total_order_bahan += $val->total_order;
        }
        $this->total_order_bahan = $total_order_bahan;
        $total_biaya_produksi=0;
        foreach($this->prosesTemps() as $val){
            $total_biaya_produksi += $val->total_biaya;
        }
        $this->total_biaya_produksi = $total_biaya_produksi;
        
        $total = $total_order_material + $total_order_bahan + $total_biaya_produksi;
        $total_ppn=0;
        if(!empty($this->ppn)){
            $total_ppn = ceil($total * ($this->ppn / 100));
        }
        $this->total_ppn = $total_ppn;
        $this->grand_total = $total+$total_ppn;
        return true;
    }

    public function getUpdateTotalOrder()
    {
        $total_order_material=0;
        foreach($this->itemsMaterial as $val){
            $total_order_material += $val->total_order;
        }
        $this->total_order_material = $total_order_material;
        $total_order_bahan=0;
        foreach($this->itemsNonMaterial as $val){
            $total_order_bahan += $val->total_order;
        }
        $this->total_order_bahan = $total_order_bahan;
        $total_biaya_produksi=0;
        foreach($this->proses as $val){
            $total_biaya_produksi += $val->total_biaya;
        }
        $this->total_biaya_produksi = $total_biaya_produksi;
        
        $total = $total_order_material + $total_order_bahan + $total_biaya_produksi;
        $total_ppn=0;
        if(!empty($this->ppn)){
            $total_ppn = ceil($total * ($this->ppn / 100));
        }
        $this->total_ppn = $total_ppn;
        $this->grand_total = $total+$total_ppn;
        return true;
    }
}