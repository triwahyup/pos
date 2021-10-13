<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterOrder;
use app\modules\master\models\MasterPerson;
use app\modules\sales\models\SalesOrderDetail;
use app\modules\sales\models\SalesOrderProduksiDetail;
use app\modules\sales\models\TempSalesOrderDetail;
use app\modules\sales\models\TempSalesOrderProduksiDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sales_order".
 *
 * @property string $no_so
 * @property string|null $tgl_so
 * @property string|null $no_po
 * @property string|null $tgl_po
 * @property string|null $customer_code
 * @property float|null $ppn
 * @property float|null $total_order
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SalesOrder extends \yii\db\ActiveRecord
{
    public $nama_order;

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
            [['tgl_so', 'customer_code', 'nama_order'], 'required'],
            [['tgl_so', 'tgl_po', 'total_order', 'total_biaya', 'grand_total'], 'safe'],
            [['ppn'], 'number'],
            [['status', 'created_at', 'updated_at', 'type_order', 'post'], 'integer'],
            [['no_so', 'no_po'], 'string', 'max' => 12],
            [['customer_code', 'outsource_code', 'order_code'], 'string', 'max' => 3],
            [['keterangan', 'nama_order'], 'string', 'max' => 128],
            [['no_so'], 'unique'],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_so' => 'No. So',
            'tgl_so' => 'Tgl. So',
            'no_po' => 'No. Po',
            'tgl_po' => 'Tgl. Po',
            'customer_code' => 'Customer',
            'ppn' => 'Ppn (%)',
            'total_order' => 'Total Order',
            'grand_total' => 'Total Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'outsource_code' => 'Jasa / Outsourcing',
            'nama_order' => 'Nama Job (Order)',
        ];
    }

    public function generateCode()
    {
        $model = SalesOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = SalesOrder::find()->orderBy(['no_so'=>SORT_DESC])->one();
            $total = (int)substr($model->no_so, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function beforeSave($attribute)
    {
        $this->total_order = str_replace(',', '', $this->total_order);
        $this->total_biaya = str_replace(',', '', $this->total_biaya);
        $this->grand_total = str_replace(',', '', $this->grand_total);
        return parent::beforeSave($attribute);
    }

    public function grand_total()
    {
        $grand_total = str_replace(',', '', $this->grand_total);
        if(!empty($this->ppn)){
            $ppn = $grand_total / ($this->ppn*100);
            $grand_total += $ppn;
        }
        return $grand_total;
    }

    public function getDetails()
    {
        return $this->hasMany(SalesOrderDetail::className(), ['no_so' => 'no_so']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempSalesOrderDetail::className(), ['no_so' => 'no_so']);
    }

    public function temps()
    {
        return TempSalesOrderDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(SalesOrderProduksiDetail::className(), ['no_so' => 'no_so']);
    }

    public function getTempsProduksi()
    {
        return $this->hasMany(TempSalesOrderProduksiDetail::className(), ['no_so' => 'no_so']);
    }

    public function tempsProduksi()
    {
        return TempSalesOrderProduksiDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getCustomer()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'customer_code']);
    }

    public function getOutsource()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'outsource_code']);
    }

    public function getPerson()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'customer_code']);
    }

    public function getOrder()
    {
        return $this->hasOne(MasterOrder::className(), ['code' => 'order_code']);
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
