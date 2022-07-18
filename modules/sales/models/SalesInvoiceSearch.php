<?php

namespace app\modules\sales\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sales\models\SalesInvoice;

/**
 * SalesInvoiceSearch represents the model behind the search form of `app\modules\sales\models\SalesInvoice`.
 */
class SalesInvoiceSearch extends SalesInvoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_invoice', 'tgl_invoice', 'keterangan'], 'safe'],
            [['ppn', 'total_order_material', 'total_order_bahan', 'total_biaya_produksi', 'total_ppn', 'grand_total', 'new_total_order_material', 'new_total_order_bahan', 'new_total_biaya_produksi', 'new_total_ppn', 'new_grand_total'], 'number'],
            [['status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SalesInvoice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tgl_invoice' => $this->tgl_invoice,
            'ppn' => $this->ppn,
            'total_order_material' => $this->total_order_material,
            'total_order_bahan' => $this->total_order_bahan,
            'total_biaya_produksi' => $this->total_biaya_produksi,
            'total_ppn' => $this->total_ppn,
            'grand_total' => $this->grand_total,
            'new_total_order_material' => $this->new_total_order_material,
            'new_total_order_bahan' => $this->new_total_order_bahan,
            'new_total_biaya_produksi' => $this->new_total_biaya_produksi,
            'new_total_ppn' => $this->new_total_ppn,
            'new_grand_total' => $this->new_grand_total,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'no_invoice', $this->no_invoice])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
