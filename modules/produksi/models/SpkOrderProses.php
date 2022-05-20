<?php

namespace app\modules\produksi\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "spk_order_proses".
 *
 * @property string $no_spk
 * @property string $item_code
 * @property int $proses_id
 * @property int $urutan
 * @property string|null $tgl_spk
 * @property int|null $potong_id
 * @property string|null $uk_potong
 * @property string|null $proses_code
 * @property int|null $proses_type
 * @property string|null $outsource_code
 * @property string|null $mesin_code
 * @property string|null $mesin_type
 * @property string|null $no_sj
 * @property string|null $nopol
 * @property float|null $qty_proses
 * @property float|null $qty_hasil
 * @property float|null $qty_rusak
 * @property float|null $gram
 * @property int|null $user_id
 * @property string|null $keterangan
 * @property int|null $status_produksi
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SpkOrderProses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spk_order_proses';
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
            [['no_spk', 'item_code', 'proses_id', 'urutan'], 'required'],
            [['proses_id', 'urutan', 'potong_id', 'proses_type', 'user_id', 'status_produksi', 'status', 'created_at', 'updated_at'], 'integer'],
            [['tgl_spk'], 'safe'],
            [['qty_proses', 'qty_hasil', 'qty_rusak', 'gram'], 'number'],
            [['no_spk', 'uk_potong', 'no_sj', 'nopol'], 'string', 'max' => 12],
            [['item_code'], 'string', 'max' => 7],
            [['proses_code', 'outsource_code', 'mesin_code', 'mesin_type'], 'string', 'max' => 3],
            [['keterangan'], 'string', 'max' => 128],
            [['no_spk', 'item_code', 'proses_id'], 'unique', 'targetAttribute' => ['no_spk', 'item_code', 'proses_id']],
            [['status'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'no_spk' => 'No Spk',
            'item_code' => 'Item Code',
            'proses_id' => 'Proses ID',
            'urutan' => 'Urutan',
            'tgl_spk' => 'Tgl Spk',
            'potong_id' => 'Potong ID',
            'uk_potong' => 'Uk Potong',
            'proses_code' => 'Proses Code',
            'proses_type' => 'Proses Type',
            'outsource_code' => 'Outsource Code',
            'mesin_code' => 'Mesin Code',
            'mesin_type' => 'Mesin Type',
            'no_sj' => 'No Sj',
            'nopol' => 'Nopol',
            'qty_proses' => 'Qty Proses',
            'qty_hasil' => 'Qty Hasil',
            'qty_rusak' => 'Qty Rusak',
            'gram' => 'Gram',
            'user_id' => 'User ID',
            'keterangan' => 'Keterangan',
            'status_produksi' => 'Status Produksi',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
