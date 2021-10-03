<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterOrderDetail;
use app\modules\master\models\MasterOrderProduksiDetail;
use app\modules\master\models\TempMasterOrderDetail;
use app\modules\master\models\TempMasterOrderProduksiDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_order".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_order';
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
            [['name', 'type_order', 'total_biaya'], 'required'],
            [['total_biaya'], 'safe'],
            [['status', 'created_at', 'updated_at', 'type_order'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name', 'keterangan'], 'string', 'max' => 128],
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
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateCode()
    {
        $model = MasterOrder::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterOrder::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, 1);
        }
        return (string)sprintf('%03s', ($total+1));
    }

    public function beforeSave($attribute)
    {
        $this->total_biaya = str_replace(',', '', $this->total_biaya);
        return parent::beforeSave($attribute);
    }

    public function getDetails()
    {
        return $this->hasMany(MasterOrderDetail::className(), ['order_code' => 'code']);
    }

    public function getTemps()
    {
        return $this->hasMany(TempMasterOrderDetail::className(), ['order_code' => 'code']);
    }

    public function temps()
    {
        return TempMasterOrderDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }

    public function getDetailsProduksi()
    {
        return $this->hasMany(MasterOrderProduksiDetail::className(), ['order_code' => 'code']);
    }

    public function getTempsProduksi()
    {
        return $this->hasMany(TempMasterOrderProduksiDetail::className(), ['order_code' => 'code']);
    }

    public function tempsProduksi()
    {
        return TempMasterOrderProduksiDetail::find()->where(['user_id' => \Yii::$app->user->id])->all();
    }
}
