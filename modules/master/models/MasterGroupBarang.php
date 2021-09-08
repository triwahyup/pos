<?php

namespace app\modules\master\models;

use Yii;
use app\modules\master\models\MasterAccountsDetail;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "master_group_barang".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $acc_persediaan_code
 * @property int|null $acc_persediaan_urutan
 * @property string|null $acc_penjualan_code
 * @property int|null $acc_penjualan_urutan
 * @property string|null $acc_hpp_code
 * @property int|null $acc_hpp_urutan
 * @property string|null $keterangan
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class MasterGroupBarang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_group_barang';
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
            [['name', 'acc_persediaan_urutan', 'acc_penjualan_urutan', 'acc_hpp_urutan'], 'required'],
            [['acc_persediaan_urutan', 'acc_penjualan_urutan', 'acc_hpp_urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['keterangan'], 'string'],
            [['code'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 128],
            [['acc_persediaan_code', 'acc_penjualan_code', 'acc_hpp_code'], 'string', 'max' => 3],
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
            'acc_persediaan_code' => 'Persediaan',
            'acc_persediaan_urutan' => 'Persediaan',
            'acc_penjualan_code' => 'Penjualan',
            'acc_penjualan_urutan' => 'Penjualan',
            'acc_hpp_code' => 'HPP',
            'acc_hpp_urutan' => 'HPP',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function newcode()
    {
        $model = MasterGroupBarang::find()->count();
        $total=0;
        if($model > 0){
            $model = MasterGroupBarang::find()->orderBy(['code'=>SORT_DESC])->one();
            $total = (int)substr($model->code, -3);
        }
        return (string)'GRUP-'.sprintf('%03s', ($total+1));
    }

    public function getPersediaan()
    {
        return $this->hasOne(MasterAccountsDetail::className(), ['accounts_code' => 'acc_persediaan_code', 'urutan' => 'acc_persediaan_urutan']);
    }

    public function getPenjualan()
    {
        return $this->hasOne(MasterAccountsDetail::className(), ['accounts_code' => 'acc_penjualan_code', 'urutan' => 'acc_penjualan_urutan']);
    }

    public function getHpp()
    {
        return $this->hasOne(MasterAccountsDetail::className(), ['accounts_code' => 'acc_hpp_code', 'urutan' => 'acc_hpp_urutan']);
    }
}
