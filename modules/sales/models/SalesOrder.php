<?php

namespace app\modules\sales\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $tgl_so
 * @property string|null $no_po
 * @property string|null $tgl_po
 * @property string|null $customer_code
 * @property int|null $ekspedisi_flag
 * @property string|null $ekspedisi_code
 * @property int|null $term_in
 * @property string|null $deadline
 * @property int|null $type_order
 * @property int|null $up_produksi
 * @property float|null $ppn
 * @property float|null $total_order_material
 * @property float|null $total_order_bahan
 * @property float|null $total_biaya_produksi
 * @property float|null $total_ppn
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
            [['name', 'type_order', 'customer_code', 'no_po', 'ekspedisi_flag', 'total_qty', 'satuan_ikat_code', 'total_warna'], 'required'],
            [['tgl_so', 'tgl_po', 'deadline', 'total_qty', 'total_qty_up'], 'safe'],
            [['sales_code', 'ekspedisi_flag', 'term_in', 'type_order', 'type_qty', 'lembar_ikat_1', 'lembar_ikat_2', 'lembar_ikat_3', 'up_produksi', 'total_warna', 'post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ppn', 'total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_ppn', 'grand_total'], 'number'],
            [['nick_name'], 'string', 'max' => 4],
            [['lembar_ikat_um_1', 'lembar_ikat_um_2', 'lembar_ikat_um_3'], 'string', 'max' => 5],
            [['code', 'no_po'], 'string', 'max' => 12],
            [['repeat_code'], 'string', 'max' => 16],
            [['name', 'keterangan'], 'string', 'max' => 128],
            [['customer_code', 'ekspedisi_code', 'satuan_ikat_code'], 'string', 'max' => 3],
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
            'tgl_so' => 'Tgl So',
            'no_po' => 'No Po',
            'tgl_po' => 'Tgl Po',
            'customer_code' => 'Customer',
            'ekspedisi_flag' => 'Ekspedisi Flag',
            'ekspedisi_code' => 'Ekspedisi',
            'term_in' => 'Term In',
            'deadline' => 'Deadline',
            'type_order' => 'Type Order',
            'up_produksi' => 'Up Produksi',
            'ppn' => 'Ppn',
            'total_order_material' => 'Total Order Material',
            'total_order_bahan' => 'Total Order Bahan',
            'total_biaya_produksi' => 'Total Biaya Produksi',
            'total_ppn' => 'Total Ppn',
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
        $this->total_warna = str_replace(',', '', $this->total_warna);
        $this->lembar_ikat_1 = str_replace(',', '', $this->lembar_ikat_1);
        $this->lembar_ikat_2 = str_replace(',', '', $this->lembar_ikat_2);
        $this->lembar_ikat_3 = str_replace(',', '', $this->lembar_ikat_3);
        return parent::beforeSave($attribute);
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

    public function getItemsMaterial()
    {
        $model = SalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.code'=>$this->code, 'value'=>\Yii::$app->params['TYPE_KERTAS']])
            ->all();
        return $model;
    }

    public function getItemsNonMaterial()
    {
        $model = SalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['a.code'=>$this->code])
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"')
            ->all();
        return $model;
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

    public function itemsMaterial()
    {
        $model = TempSalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['value'=>\Yii::$app->params['TYPE_KERTAS'], 'user_id' => \Yii::$app->user->id])
            ->all();
        return $model;
    }

    public function itemsNonMaterial()
    {
        $model = TempSalesOrderItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->where(['user_id' => \Yii::$app->user->id])
            ->andWhere('value <> "'.\Yii::$app->params['TYPE_KERTAS'].'"')
            ->all();
        return $model;
    }

    public function getTypeOrder()
    {
        $message = '';
        if($this->type_order == 1){
            $message = 'Produksi';
        }else if($this->type_order == 2){
            $message = 'Jasa';
        }else{
            $message = 'Sample';
        }
        return $message;
    }
    
    public function getStatusPost()
    {
        $message = '';
        if($this->post == 1){
            $message = '<span class="text-label text-success">Sudah Post</span>';
        }else if($this->post == 2){
            $message = '<span class="text-label text-danger">Cancel Order</span>';
        }else if($this->post == 3){
            $message = '<span class="text-label text-primary">Sudah Invoice</span>';
        }else{
            $message = '<span class="text-label text-default">Belum Post</span>';
        }
        return $message;
    }

    public function getTotalOrder()
    {
        $total_order_material=0;
        foreach($this->itemsMaterial() as $val){
            $total_order_material += $val->total_order;
        }
        $this->total_order_material = $total_order_material;
        $total_order_bahan=0;
        foreach($this->itemsNonMaterial() as $val){
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
}
