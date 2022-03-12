<?php

namespace app\modules\sales\models;

use Yii;
use app\modules\master\models\MasterMaterialItem;

/**
 * This is the model class for table "temp_sales_order_potong".
 *
 * @property int $id
 * @property string $code
 * @property int $urutan
 * @property string|null $item_code
 * @property float|null $panjang
 * @property float|null $lebar
 * @property int|null $objek
 * @property float|null $total_objek
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
            [['code', 'urutan'], 'required'],
            [['urutan', 'objek', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'waste', 'total_objek'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
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
            'urutan' => 'Urutan',
            'item_code' => 'Item Code',
            'panjang' => 'Panjang',
            'lebar' => 'Lebar',
            'objek' => 'Objek',
            'total_objek' => 'Total Objek',
            'user_id' => 'User ID',
        ];
    }

    public function getTemps()
    {
        return TempSalesOrderPotong::find()->where(['code'=>$this->code, 'user_id'=> \Yii::$app->user->id])->all();
    }

    public function getCountTemp()
    {
        return TempSalesOrderPotong::find()
            ->where(['code'=>$this->code, 'item_code'=>$this->item_code, 'user_id'=> \Yii::$app->user->id])
            ->count();
    }

    public function checkUkPotong($code, $item_code)
    {
        $temps = TempSalesOrderPotong::find()
            ->where(['code'=>$code, 'item_code'=>$item_code, 'user_id'=> \Yii::$app->user->id])
            ->all();
        $item = MasterMaterialItem::findOne(['code'=>$item_code]);
        $item_lebar = $item->lebar;
        $total_lebar = 0;
        foreach($temps as $val){
            $total_lebar += $val->lebar;
        }
        $sisa_potong = $item_lebar - $total_lebar;
        $total_lebar = $total_lebar + $this->lebar;
        $waste = $item_lebar - $total_lebar;
        return ['total_lebar' => $total_lebar, 'sisa_potong' => $sisa_potong, 'waste' => $waste];
    }
}
