<?php

namespace app\modules\purchasing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\purchasing\models\PurchaseOrder;

/**
 * PurchaseOrderSearch represents the model behind the search form of `app\modules\purchasing\models\PurchaseOrder`.
 */
class PurchaseOrderSearch extends PurchaseOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_po', 'tgl_po', 'tgl_kirim', 'supplier_code'], 'safe'],
            [['term_in', 'status_approval', 'status_terima', 'post'], 'integer'],
            [['total_order'], 'number'],
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
        $query = PurchaseOrder::find()
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
        $query->andFilterWhere([
            'tgl_po' => $this->tgl_po,
            'tgl_kirim' => $this->tgl_kirim,
            'term_in' => $this->term_in,
            'total_order' => $this->total_order,
            'status_approval' => $this->status_approval,
            'status_terima' => $this->status_terima,
            'post' => $this->post,
        ]);
        if(!empty($this->supplier_code)){
            $query->andWhere('b.code LIKE "%'.$this->supplier_code.'%" OR b.name LIKE "%'.$this->supplier_code.'%"');
        }

        $query->andFilterWhere(['like', 'no_po', $this->no_po]);

        return $dataProvider;
    }
}
