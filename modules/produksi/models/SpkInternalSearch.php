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
            [['no_spk', 'tgl_spk', 'no_so', 'tgl_so', 'keterangan_cetak', 'keterangan_potong', 'keterangan_pond'], 'safe'],
            [['status', 'status_produksi', 'created_at', 'updated_at'], 'integer'],
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
            'status' => $this->status,
            'status_produksi' => $this->status_produksi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'no_spk', $this->no_spk])
            ->andFilterWhere(['like', 'no_so', $this->no_so])
            ->andFilterWhere(['like', 'keterangan_cetak', $this->keterangan_cetak])
            ->andFilterWhere(['like', 'keterangan_potong', $this->keterangan_potong])
            ->andFilterWhere(['like', 'keterangan_pond', $this->keterangan_pond]);

        return $dataProvider;
    }
}
