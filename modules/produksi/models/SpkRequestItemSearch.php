<?php

namespace app\modules\produksi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\produksi\models\SpkRequestItem;

/**
 * SpkRequestItemSearch represents the model behind the search form of `app\modules\produksi\models\SpkRequestItem`.
 */
class SpkRequestItemSearch extends SpkRequestItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_request', 'tgl_request', 'no_spk', 'keterangan'], 'safe'],
            [['status_approval', 'status', 'created_at', 'updated_at'], 'integer'],
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
        $query = SpkRequestItem::find();

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
            'tgl_request' => $this->tgl_request,
            'status_approval' => $this->status_approval,
            'status' => 1,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'no_request', $this->no_request])
            ->andFilterWhere(['like', 'no_spk', $this->no_spk])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
