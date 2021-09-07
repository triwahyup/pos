<?php

namespace app\modules\master\models;

use Yii;

/**
 * This is the model class for table "master_group_barang".
 *
 * @property string $code
 * @property string|null $name
 * @property string|null $acc_persedian_code
 * @property int|null $acc_persedian_urutan
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['acc_persedian_urutan', 'acc_penjualan_urutan', 'acc_hpp_urutan', 'status', 'created_at', 'updated_at'], 'integer'],
            [['keterangan'], 'string'],
            [['code'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 128],
            [['acc_persedian_code', 'acc_penjualan_code', 'acc_hpp_code'], 'string', 'max' => 3],
            [['code'], 'unique'],
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
            'acc_persedian_code' => 'Acc Persedian Code',
            'acc_persedian_urutan' => 'Acc Persedian Urutan',
            'acc_penjualan_code' => 'Acc Penjualan Code',
            'acc_penjualan_urutan' => 'Acc Penjualan Urutan',
            'acc_hpp_code' => 'Acc Hpp Code',
            'acc_hpp_urutan' => 'Acc Hpp Urutan',
            'keterangan' => 'Keterangan',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
