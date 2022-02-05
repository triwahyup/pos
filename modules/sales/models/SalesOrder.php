<?php

namespace app\modules\sales\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\master\models\MasterPerson;
use app\modules\sales\models\SalesOrderDetail;
use app\modules\sales\models\SalesOrderItem;
use app\modules\sales\models\TempSalesOrderDetail;
use app\modules\sales\models\TempSalesOrderItem;

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
 * @property float|null $biaya_pengiriman
 * @property float|null $ppn
 * @property float|null $total_order
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
            [['name', 'type_order', 'customer_code', 'no_po', 'ekspedisi_name', 'biaya_pengiriman'], 'required'],
            [['tgl_so', 'tgl_po'], 'safe'],
            [['type_order', 'up_produksi', 'post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['biaya_pengiriman', 'ppn', 'total_order', 'total_biaya_produksi', 'grand_total'], 'number'],
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
            'type_order' => 'Type Order',
            'up_produksi' => 'Up Produksi',
            'ekspedisi_name' => 'Ekspedisi Name',
            'biaya_pengiriman' => 'Biaya Pengiriman',
            'ppn' => 'Ppn',
            'total_order' => 'Total Order',
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
        $this->biaya_pengiriman = str_replace(',', '', $this->biaya_pengiriman);
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
        return SalesOrderItem::find()
            ->where(['code' => $this->code])
            ->andWhere('type_code <> "'.$this->type_material.'"')
            ->all();
    }

    public function getItems()
    {
        return $this->hasMany(SalesOrderItem::className(), ['code' => 'code']);
    }

    public function getDetails()
    {
        return $this->hasMany(SalesOrderDetail::className(), ['code' => 'code']);
    }

    public function getDetailTemps()
    {
        return $this->hasMany(TempSalesOrderDetail::className(), ['code' => 'code']);
    }

    public function getItemTemps()
    {
        return $this->hasMany(TempSalesOrderItem::className(), ['code' => 'code']);
    }

    public function detailTemps()
    {
        return TempSalesOrderDetail::findAll(['user_id' => \Yii::$app->user->id]);
    }

    public function itemTemps()
    {
        return TempSalesOrderItem::findAll(['user_id' => \Yii::$app->user->id]);
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
}