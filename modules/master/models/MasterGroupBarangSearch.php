<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterGroupBarang;

/**
 * MasterGroupBarangSearch represents the model behind the search form of `app\modules\master\models\MasterGroupBarang`.
 */
class MasterGroupBarangSearch extends MasterGroupBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'acc_persedian_code', 'acc_penjualan_code', 'acc_hpp_code', 'keterangan'], 'safe'],
            [['acc_persedian_urutan', 'acc_penjualan_urutan', 'acc_hpp_urutan', 'status', 'created_at', 'updated_at'], 'integer'],
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
        $query = MasterGroupBarang::find();

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
            'acc_persedian_urutan' => $this->acc_persedian_urutan,
            'acc_penjualan_urutan' => $this->acc_penjualan_urutan,
            'acc_hpp_urutan' => $this->acc_hpp_urutan,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'acc_persedian_code', $this->acc_persedian_code])
            ->andFilterWhere(['like', 'acc_penjualan_code', $this->acc_penjualan_code])
            ->andFilterWhere(['like', 'acc_hpp_code', $this->acc_hpp_code])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
