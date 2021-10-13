<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterOrder;

/**
 * MasterOrderSearch represents the model behind the search form of `app\modules\master\models\MasterOrder`.
 */
class MasterOrderSearch extends MasterOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_order', 'total_biaya', 'total_order', 'grand_total'], 'safe'],
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
        $query = MasterOrder::find();

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
        $query->where(['status' => 1]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'total_order', $this->total_order])
            ->andFilterWhere(['like', 'total_biaya', $this->total_biaya])
            ->andFilterWhere(['like', 'grand_total', $this->grand_total]);
        return $dataProvider;
    }
}
