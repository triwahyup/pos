<?php

namespace app\modules\produksi\models;

use Yii;

/**
 * This is the model class for table "spk_internal".
 *
 * @property string $no_spk
 * @property string|null $tgl_spk
 * @property string|null $no_so
 * @property string|null $tgl_so
 * @property string|null $keterangan_cetak
 * @property string|null $keterangan_potong
 * @property string|null $keterangan_pond
 * @property int|null $status
 * @property int|null $status_produksi
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkInternal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_internal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_spk'], 'required'],
            [['tgl_spk', 'tgl_so'], 'safe'],
            [['status', 'status_produksi', 'created_at', 'updated_at'], 'integer'],
            [['no_spk', 'no_so'], 'string', 'max' => 12],
            [['keterangan_cetak', 'keterangan_potong', 'keterangan_pond'], 'string', 'max' => 128],
            [['no_spk'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_spk' => 'No Spk',
            'tgl_spk' => 'Tgl Spk',
            'no_so' => 'No So',
            'tgl_so' => 'Tgl So',
            'keterangan_cetak' => 'Keterangan Cetak',
            'keterangan_potong' => 'Keterangan Potong',
            'keterangan_pond' => 'Keterangan Pond',
            'status' => 'Status',
            'status_produksi' => 'Status Produksi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
