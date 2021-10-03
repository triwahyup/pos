<?php

namespace app\modules\sales\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sales\models\SalesOrder;

/**
 * SalesOrderSearch represents the model behind the search form of `app\modules\sales\models\SalesOrder`.
 */
class SalesOrderSearch extends SalesOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_so', 'tgl_so', 'no_po', 'tgl_po', 'customer_code'], 'safe'],
            [['ppn', 'total_order'], 'number'],
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
        $query = SalesOrder::find();

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
            'tgl_so' => $this->tgl_so,
            'tgl_po' => $this->tgl_po,
            'ppn' => $this->ppn,
            'total_order' => $this->total_order,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'no_so', $this->no_so])
            ->andFilterWhere(['like', 'no_po', $this->no_po])
            ->andFilterWhere(['like', 'customer_code', $this->customer_code]);

        return $dataProvider;
    }
}
