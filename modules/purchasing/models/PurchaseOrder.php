<?php

namespace app\modules\purchasing\models;

use Yii;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\Profile;
use app\modules\purchasing\models\PurchaseOrderApproval;
use app\modules\purchasing\models\PurchaseOrderDetail;
use app\modules\purchasing\models\TempPurchaseOrderDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_order".
 *
 * @property string $no_po
 * @property string|null $tgl_po
 * @property string|null $tgl_kirim
 * @property int|null $term_in
 * @property string|null $supplier_code
 * @property string|null $keterangan
 * @property float|null $total_order
 * @property int|null $user_id
 * @property int|null $status
 * @property int|null $status_approval
 * @property int|null $status_terima
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PurchaseOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_order';
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
            [['tgl_po', 'tgl_kirim', 'term_in', 'supplier_code', 'user_request'], 'required'],
            [['tgl_po', 'tgl_kirim', 'total_order'], 'safe'],
            [['term_in', 'user_id', 'user_request', 'status', 'status_approval', 'status_terima', 'created_at', 'updated_at', 'post'], 'integer'],
            [['keterangan'], 'string'],
            [['no_po'], 'string', 'max' => 12],
            [['supplier_code', 'type_material'], 'string', 'max' => 3],
            [['no_po'], 'unique'],
            [['status_approval', 'status_terima', 'post'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_po' => 'No. Po',
            'tgl_po' => 'Tgl. Po',
            'tgl_kirim' => 'Tgl. Kirim',
            'term_in' => 'Term In',
            'supplier_code' => 'Supplier',
            'keterangan' => 'Keterangan',
            'total_order' => 'Total Order',
            'user_id' => 'User Create',
            'user_request' => 'Request By',
            'status' => 'Status',
            'status_approval' => 'Status Approval',
            'status_terima' => 'Status Terima',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attribute)
    {
        $this->tgl_po = date('Y-m-d', strtotime($this->tgl_po));
        $this->tgl_kirim = date('Y-m-d', strtotime($this->tgl_kirim));
        $this->total_order = str_replace(',', '', $this->total_order);
        return parent::beforeSave($attribute);
    }

    public function generateCode()
    {
        $model = PurchaseOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = PurchaseOrder::find()->orderBy(['no_po'=>SORT_DESC])->one();
            $total = (int)substr($model->no_po, -4);
        }
        return (string)date('Ymd').sprintf('%04s', ($total+1));
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }

    public function getTypeMaterial()
    {
        return $this->hasOne(MasterKode::className(), ['code' => 'type_material']);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id']);
    }

    public function getRequest()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_request']);
    }

    public function getDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::className(), ['no_po' => 'no_po']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempPurchaseOrderDetail::className(), ['no_po' => 'no_po']);
    }

    public function temps()
    {
        return TempPurchaseOrderDetail::findAll(['user_id' => \Yii::$app->user->id]);
    }

    public function getApprovals()
    {
        return $this->hasMany(PurchaseOrderApproval::className(), ['no_po' => 'no_po']);
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

    public function getStatusApproval()
    {
        $message = '';
        if($this->status_approval == 1){
            $message = '<span class="text-label text-primary">Send Approval</span>';
        }else if($this->status_approval == 2){
            $message = '<span class="text-label text-success">Finish</span>';
        }else if($this->status_approval == 3){
            $message = '<span class="text-label text-danger">Rejected</span>';
        }else{
            $message = '<span class="text-label text-default">Not Send</span>';
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