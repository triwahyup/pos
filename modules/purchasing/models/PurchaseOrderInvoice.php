<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterPerson;
use app\modules\purchasing\models\PurchaseOrder;
use app\modules\purchasing\models\PurchaseOrderInvoiceDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_order_invoice".
 *
 * @property string $no_invoice
 * @property string|null $tgl_invoice
 * @property string|null $no_bukti
 * @property string|null $no_po
 * @property string|null $tgl_po
 * @property string|null $tgl_kirim
 * @property int|null $term_in
 * @property string|null $supplier_code
 * @property string|null $keterangan
 * @property float|null $total_ppn
 * @property float|null $total_order
 * @property float|null $total_invoice
 * @property int|null $user_id
 * @property int|null $post
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseOrderInvoice extends \yii\db\ActiveRecord
{
    public $qty_terima_1;
    public $qty_terima_2;
    public $qty_terima_3;
    public $harga_beli_1;
    public $harga_beli_2;
    public $harga_beli_3;
    public $ppn;
    public $urutan;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order_invoice';
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
            [['tgl_invoice', 'tgl_po', 'tgl_kirim', 'total_invoice', 'qty_terima_1', 'qty_terima_2', 'qty_terima_3', 'harga_beli_1', 'harga_beli_2', 'harga_beli_3'], 'safe'],
            [['term_in', 'user_id', 'post', 'status', 'created_at', 'updated_at', 'status_terima', 'urutan'], 'integer'],
            [['keterangan'], 'string'],
            [['total_ppn', 'total_order', 'ppn'], 'number'],
            [['no_invoice', 'no_po'], 'string', 'max' => 12],
            [['no_bukti'], 'string', 'max' => 32],
            [['supplier_code'], 'string', 'max' => 3],
            [['no_invoice'], 'unique'],
            [['status_terima'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_invoice' => 'No. Invoice',
            'tgl_invoice' => 'Tgl. Invoice',
            'no_bukti' => 'No. Bukti',
            'no_po' => 'No. Po',
            'tgl_po' => 'Tgl. Po',
            'tgl_kirim' => 'Tgl Kirim',
            'term_in' => 'Term In',
            'supplier_code' => 'Supplier',
            'keterangan' => 'Keterangan',
            'total_ppn' => 'Total Ppn',
            'total_order' => 'Total Order',
            'total_invoice' => 'Total Invoice',
            'user_id' => 'User ID',
            'post' => 'Status Stok',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ppn' => 'Ppn (%)',
            'harga_beli_1' => 'Harga Beli 1',
            'harga_beli_2' => 'Harga Beli 2',
            'harga_beli_3' => 'Harga Beli 3',
            'qty_terima_1' => 'QTY Terima 1',
            'qty_terima_2' => 'QTY Terima 2',
            'qty_terima_3' => 'QTY Terima 3',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->total_invoice = str_replace(',', '', $this->total_invoice);
        return parent::beforeSave($attribute);
    }

    public function generateCode()
    {
        $model = PurchaseOrderInvoice::find()->count();
        $total=0;
        if($model > 0){
            $model = PurchaseOrderInvoice::find()->orderBy(['no_invoice'=>SORT_DESC])->one();
            $total = (int)substr($model->no_invoice, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getPurchase()
    {
        return $this->hasOne(PurchaseOrder::className(), ['no_po' => 'no_po']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getDetails()
    {
        return $this->hasMany(PurchaseOrderInvoiceDetail::className(), ['no_invoice' => 'no_invoice']);
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

    public function getStatusTerima()
    {
        $message = '';
        if($this->status_terima == 1){
            $message = '<span class="text-label text-success">Sudah Terima</span>';
        }else{
            $message = '<span class="text-label text-default">Belum Terima</span>';
        }
        return $message;
    }
}