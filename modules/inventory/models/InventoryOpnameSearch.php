<?php

namespace app\modules\inventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\InventoryOpname;

/**
 * InventoryOpnameSearch represents the model behind the search form of `app\modules\inventory\models\InventoryOpname`.
 */
class InventoryOpnameSearch extends InventoryOpname
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'date', 'supplier_code', 'post', 'status_approval'], 'safe'],
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
        $query = InventoryOpname::find()
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
        $query->andFilterWhere([
            'post' => $this->post,
            'status_approval' => $this->status_approval,
            'a.status' => 1
        ]);
        if(!empty($this->date)){
            $query->andFilterWhere(['date' => date('Y-m-d', strtotime($this->date))]);
        }
        if(!empty($this->supplier_code)){
            $query->andWhere('b.name LIKE "%'.$this->supplier_code.'%"');
        }
        $query->andFilterWhere(['like', 'a.code', $this->code]);

        return $dataProvider;
    }
}
