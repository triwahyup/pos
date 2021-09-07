<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterGroupSupplier;

/**
 * MasterGroupSupplierSearch represents the model behind the search form of `app\modules\master\models\MasterGroupSupplier`.
 */
class MasterGroupSupplierSearch extends MasterGroupSupplier
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'keterangan'], 'safe'],
            [['status', 'created_at', 'updated_at'], 'integer'],
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
        $query = MasterGroupSupplier::find();

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
