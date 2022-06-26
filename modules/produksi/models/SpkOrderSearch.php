<?php

namespace app\modules\produksi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\produksi\models\SpkOrder;

/**
 * SpkOrderSearch represents the model behind the search form of `app\modules\produksi\models\SpkOrder`.
 */
class SpkOrderSearch extends SpkOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_spk', 'tgl_spk', 'no_so', 'tgl_so', 'name', 'deadline', 'status_produksi'], 'safe'],
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
        $query = SpkOrder::find()->orderBy(['no_spk' => SORT_DESC]);

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
        $query->where(['status'=>1]);
        if(!empty($this->tgl_spk)){
            $query->andFilterWhere(['tgl_spk' => date('Y-m-d', strtotime($this->tgl_spk))]);
        }
        if(!empty($this->tgl_so)){
            $query->andFilterWhere(['tgl_so' => date('Y-m-d', strtotime($this->tgl_so))]);
        }
        if(!empty($this->deadline)){
            $query->andFilterWhere(['deadline' => date('Y-m-d', strtotime($this->deadline))]);
        }

        $query->andFilterWhere(['like', 'no_spk', $this->no_spk])
            ->andFilterWhere(['like', 'no_so', $this->no_so])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
