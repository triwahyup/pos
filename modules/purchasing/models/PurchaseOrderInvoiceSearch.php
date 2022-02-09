<?php

namespace app\modules\purchasing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\purchasing\models\PurchaseOrderInvoice;

/**
 * PurchaseOrderInvoiceSearch represents the model behind the search form of `app\modules\purchasing\models\PurchaseOrderInvoice`.
 */
class PurchaseOrderInvoiceSearch extends PurchaseOrderInvoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_invoice', 'tgl_invoice', 'no_bukti', 'no_po', 'tgl_po', 'tgl_kirim', 'supplier_code', 'keterangan'], 'safe'],
            [['term_in', 'user_id', 'post', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_ppn', 'total_order', 'total_invoice'], 'number'],
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
        $query = PurchaseOrderInvoice::find()
            ->alias('a')
            ->leftJoin('master_person b', 'b.code = a.supplier_code');

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
        $query->where(['a.status'=>1]);
        $query->andFilterWhere(['total_invoice' => $this->total_invoice, 'post' => $this->post]);
        if(!empty($this->supplier_code)){
            $query->andWhere('b.code LIKE "%'.$this->supplier_code.'%" OR b.name LIKE "%'.$this->supplier_code.'%"');
        }
        if(!empty($this->tgl_invoice)){
            $query->andFilterWhere(['tgl_invoice' => date('Y-m-d', strtotime($this->tgl_invoice))]);
        }
        $query->andFilterWhere(['like', 'no_invoice', $this->no_invoice])
            ->andFilterWhere(['like', 'no_bukti', $this->no_bukti])
            ->andFilterWhere(['like', 'no_po', $this->no_po]);

        return $dataProvider;
    }
}
