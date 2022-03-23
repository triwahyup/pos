<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterProses;

/**
 * MasterProsesSearch represents the model behind the search form of `app\modules\master\models\MasterProses`.
 */
class MasterProsesSearch extends MasterProses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'harga', 'index', 'created_at', 'updated_at', 'mesin_type', 'urutan'], 'safe'],
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
        $query = MasterProses::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.mesin_type');

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
        if(!empty($this->created_at)){
            $t1 = strtotime($this->created_at);
			$t2 = strtotime("+1 days", $t1);
			$query->andWhere('a.created_at >='.$t1.' and a.created_at <'.$t2);
        }
        if(!empty($this->updated_at)){
            $t1 = strtotime($this->updated_at);
			$t2 = strtotime("+1 days", $t1);
			$query->andWhere('a.updated_at >='.$t1.' and a.updated_at <'.$t2);
        }
        if(!empty($this->mesin_type)){
            $query->andWhere('b.code LIKE "%'.$this->mesin_type.'%" OR b.value LIKE "%'.$this->mesin_type.'%"');
        }
        $query->andFilterWhere([
            'harga' => $this->harga,
            'index' => $this->index,
            'status' => $this->status,
            'urutan' => $this->urutan
        ]);

        $query->andFilterWhere(['like', 'a.name', $this->name]);
        return $dataProvider;
    }
}
