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
            [['code', 'date', 'keterangan'], 'safe'],
            [['post', 'status_approval', 'status', 'user_id', 'created_at', 'updated_at'], 'integer'],
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
        $query = InventoryOpname::find();

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
            'status' => 1,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if(!empty($this->date)){
            $query->andFilterWhere(['date' => date('Y-m-d', strtotime($this->date))]);
        }

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
