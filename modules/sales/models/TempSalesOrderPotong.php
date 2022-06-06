<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;

/**
 * This is the model class for table "temp_sales_order_potong".
 *
 * @property int $id
 * @property string $code
 * @property string $item_code
 * @property int $urutan
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $objek
 * @property float|null $qty_sisa
 * @property int|null $user_id
 */
class TempSalesOrderPotong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_sales_order_potong';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'item_code', 'urutan'], 'required'],
            [['urutan', 'objek', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'qty_sisa'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['supplier_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'item_code' => 'Item Code',
            'urutan' => 'Urutan',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'objek' => 'Objek',
            'qty_sisa' => 'Qty Sisa',
            'user_id' => 'User ID',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(MasterMaterial::className(), ['code' => 'item_code']);
    }

    public function getSupplier()
    {
        return $this->hasOne(MasterPerson::className(), ['code' => 'supplier_code']);
    }
    
    public function getTemps()
    {
        return TempSalesOrderPotong::find()->where(['code'=>$this->code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempSalesOrderPotong::find()
            ->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'supplier_code'=>$this->supplier_code, 'user_id'=> \Yii::$app->user->id])
            ->count();
    }
}