<?php

namespace app\modules\produksi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\produksi\models\SpkInternal;

/**
 * SpkInternalSearch represents the model behind the search form of `app\modules\produksi\models\SpkInternal`.
 */
class SpkInternalSearch extends SpkInternal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_spk', 'tgl_spk', 'no_so', 'tgl_so'], 'safe'],
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
        $query = SpkInternal::find();

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
            'tgl_spk' => $this->tgl_spk,
            'tgl_so' => $this->tgl_so,
        ]);

        $query->andFilterWhere(['like', 'no_spk', $this->no_spk])
            ->andFilterWhere(['like', 'no_so', $this->no_so]);

        return $dataProvider;
    }
}
