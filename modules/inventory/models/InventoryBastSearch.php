<?php

namespace app\modules\inventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\InventoryBast;

/**
 * InventoryBastSearch represents the model behind the search form of `app\modules\inventory\models\InventoryBast`.
 */
class InventoryBastSearch extends InventoryBast
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'date', 'type_code', 'user_id', 'post'], 'safe'],
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
        $query = InventoryBast::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->leftJoin('profile c', 'c.user_id = a.user_id');

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
        if(!empty($this->date)){
            $query->andFilterWhere(['date' => date('Y-m-d', strtotime($this->date))]);
        }
        if(!empty($this->type_code)){
            $query->andWhere('b.code LIKE "%'.$this->type_code.'%" OR b.name LIKE "%'.$this->type_code.'%"');
        }
        if(!empty($this->user_id)){
            $query->andWhere('c.user_id LIKE "%'.$this->user_id.'%" OR c.name LIKE "%'.$this->user_id.'%"');
        }
        $query->andFilterWhere(['post' => $this->post]);
        $query->andFilterWhere(['like', 'code', $this->code]);
        return $dataProvider;
    }
}
