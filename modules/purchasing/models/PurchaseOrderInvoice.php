<?php

namespace app\modules\purchasing\models;

use Yii;
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
            [['no_invoice'], 'required'],
            [['tgl_invoice', 'tgl_po', 'tgl_kirim'], 'safe'],
            [['term_in', 'user_id', 'post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['keterangan'], 'string'],
            [['total_ppn', 'total_order', 'total_invoice'], 'number'],
            [['no_invoice', 'no_po'], 'string', 'max' => 12],
            [['no_bukti'], 'string', 'max' => 32],
            [['supplier_code'], 'string', 'max' => 3],
            [['no_invoice'], 'unique'],
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
            'supplier_code' => 'Supplier Code',
            'keterangan' => 'Keterangan',
            'total_ppn' => 'Total Ppn',
            'total_order' => 'Total Order',
            'total_invoice' => 'Total Invoice',
            'user_id' => 'User ID',
            'post' => 'Post',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = PurchaseOrderInvoice::find()->count();
        $total=0;
        if($model > 0){
            $model = PurchaseOrderInvoice::find()->orderBy(['no_po'=>SORT_DESC])->one();
            $total = (int)substr($model->no_po, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }
}