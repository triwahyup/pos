<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterKendaraan;

/**
 * MasterKendaraanSearch represents the model behind the search form of `app\modules\master\models\MasterKendaraan`.
 */
class MasterKendaraanSearch extends MasterKendaraan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'outsource_code', 'type_code', 'nopol', 'no_handphone', 'no_sim', 'created_at', 'updated_at'], 'safe'],
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
        $query = MasterKendaraan::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->leftJoin('master_person c', 'c.code = a.outsource_code');

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
        if(!empty($this->type_code)){
            $query->andWhere('b.name LIKE "%'.$this->type_code.'%"');
        }
        if(!empty($this->outsource_code)){
            $query->andWhere('c.name LIKE "%'.$this->outsource_code.'%"');
        }
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'nopol', $this->nopol])
            ->andFilterWhere(['like', 'no_handphone', $this->no_handphone])
            ->andFilterWhere(['like', 'no_sim', $this->no_sim]);

        return $dataProvider;
    }
}