<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterPerson;
use app\modules\purchasing\models\PurchaseInternal;
use app\modules\purchasing\models\PurchaseInternalInvoice;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_internal_invoice".
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
 * @property int|null $status_terima
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseInternalInvoice extends \yii\db\ActiveRecord
{
    public $qty_terima;
    public $harga_beli;
    public $ppn;
    public $urutan;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_internal_invoice';
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
            [['tgl_invoice', 'tgl_po', 'tgl_kirim', 'total_invoice', 'qty_terima_1', 'qty_terima', 'harga_beli'], 'safe'],
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
            'no_invoice' => 'No Invoice',
            'tgl_invoice' => 'Tgl Invoice',
            'no_bukti' => 'No Bukti',
            'no_po' => 'No Po',
            'tgl_po' => 'Tgl Po',
            'tgl_kirim' => 'Tgl Kirim',
            'term_in' => 'Term In',
            'supplier_code' => 'Supplier',
            'keterangan' => 'Keterangan',
            'total_ppn' => 'Total Ppn',
            'total_order' => 'Total Order',
            'total_invoice' => 'Total Invoice',
            'user_id' => 'User ID',
            'post' => 'Post',
            'status' => 'Status',
            'status_terima' => 'Status Terima',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ppn' => 'Ppn (%)',
            'harga_beli' => 'Harga Beli',
            'qty_terima' => 'QTY Terima',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->tgl_po = (!empty($this->tgl_po)) ? date('Y-m-d', strtotime($this->tgl_po)) : null;
        $this->tgl_invoice = (!empty($this->tgl_invoice)) ? date('Y-m-d', strtotime($this->tgl_invoice)) : null;
        $this->total_invoice = (!empty($this->total_invoice)) ? str_replace(',', '', $this->total_invoice) : null;
        return parent::beforeSave($attribute);
    }

    public function generateCode()
    {
        $model = PurchaseInternalInvoice::find()->count();
        $total=0;
        if($model > 0){
            $model = PurchaseInternalInvoice::find()->orderBy(['no_invoice'=>SORT_DESC])->one();
            $total = (int)substr($model->no_invoice, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getPurchase()
    {
        return $this->hasOne(PurchaseInternal::className(), ['no_po' => 'no_po']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getDetails()
    {
        return $this->hasMany(PurchaseInternalInvoiceDetail::className(), ['no_invoice' => 'no_invoice']);
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
        }else if($this->status_terima == 2){
            $message = '<span class="text-label text-warning">Terima Sebagian</span>';
        }else if($this->status_terima == 3){
            $message = '<span class="text-label text-primary">Not Balance</span>';
        }else{
            $message = '<span class="text-label text-default">Belum Terima</span>';
        }
        return $message;
    }
}
