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
            [['name', 'code', 'tgl_so', 'no_po', 'tgl_po', 'customer_code', 'post'], 'safe'],
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
        $query = SalesOrder::find()
            ->alias('a')
            ->leftJoin('master_person b', 'b.code = a.customer_code');

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
            'tgl_so' => $this->tgl_so,
            'tgl_po' => $this->tgl_po
        ]);

        if(!empty($this->customer_code)){
            $query->andWhere('b.code LIKE "%'.$this->customer_code.'%" OR b.name LIKE "%'.$this->customer_code.'%"');
        }
        if(!empty($this->total_order)){
            $query->andWhere('total_order LIKE "%'.str_replace(',','', $this->total_order).'%"');
        }

        $query->andFilterWhere(['like', 'a.code', $this->code])
            ->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'no_po', $this->no_po])
            ->andFilterWhere(['like', 'post', $this->post]);

        return $dataProvider;
    }
}
