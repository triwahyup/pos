<?php

namespace app\modules\produksi\models;

use Yii;
use app\modules\master\models\MasterMaterial;
use app\modules\master\models\MasterPerson;

/**
 * This is the model class for table "temp_spk_potong_roll_detail".
 *
 * @property int $id
 * @property string $code
 * @property string $item_code
 * @property int $urutan
 * @property string|null $supplier_code
 * @property int|null $user_id
 */
class TempSpkPotongRollDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_spk_potong_roll_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'item_code', 'urutan'], 'required'],
            [['urutan', 'user_id'], 'integer'],
            [['panjang', 'lebar', 'gram'], 'safe'],
            [['qty'], 'number'],
            [['code'], 'string', 'max' => 12],
            [['first_name', 'last_name'], 'string', 'max' => 32],
            [['name', 'keterangan'], 'string', 'max' => 128],
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
            'supplier_code' => 'Supplier',
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

    public function getCount()
    {
        return TempSpkPotongRollDetail::find()->where(['user_id'=> \Yii::$app->user->id])->count();
    }

    public function getTmps()
    {
        return TempSpkPotongRollDetail::find()->where(['user_id'=> \Yii::$app->user->id])->all();
    }

    public function newItemName()
    {
        $str = $this->first_name.' ';
        $str .= $this->panjang.'x'.$this->lebar.'/'.$this->gram;
        if(!empty($this->last_name)){
            $str .= ' ('.$this->last_name.')';
        }
        return $str;
    }

    public function checkPanjang($hP, $tP)
    {
        $hPanjang = str_replace(',', '', $hP);
        $tPanjang = str_replace(',', '', $tP);
        $success = false;
        if($hPanjang >= $tPanjang){
            $success = true;
        }
        return ['success'=>$success, 'hPanjang'=>$hPanjang, 'tPanjang'=>$tPanjang];
    }

    public function checkUkPotong($temp)
    {
        $temps = TempSpkPotongRollDetail::find()
            ->where(['code'=>$temp->code, 'item_code'=>$temp->item_code, 'user_id'=> \Yii::$app->user->id])
            ->all();
        $item = MasterMaterial::findOne(['code'=>$temp->item_code]);
        $item_L = $item->lebar;
        $total_L = 0;
        foreach($temps as $val){
            $total_L += $val->lebar;
        }
        
        $pembagian = (!empty($total_L)) ? $item_L / $total_L : 0;
        if($pembagian == 2){
            $sisa_potong = 0;
            $total_L = ($total_L*$pembagian);
        }else{
            $sisa_potong = $item_L - $total_L;
        }
        
        $success = true;
        $total_all = $total_L + $temp->lebar;
        if($total_all > $item_L){
            $success = false;
        }
        return ['success' => $success, 'sisa_potong' => $sisa_potong];
    }
}
