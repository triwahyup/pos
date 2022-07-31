<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterBarang;

/**
 * MasterBarangSearch represents the model behind the search form of `app\modules\master\models\MasterBarang`.
 */
class MasterBarangSearch extends MasterBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'type_code', 'satuan_code', 'created_at', 'updated_at'], 'safe'],
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
        $query = MasterBarang::find()
            ->alias('a')
            ->leftJoin('master_satuan b', 'b.code = a.satuan_code')
            ->leftJoin('master_kode c', 'c.code = a.type_code');

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
        if(!empty($this->satuan_code)){
            $query->andWhere('b.name LIKE "%'.$this->satuan_code.'%"');
        }
        if(!empty($this->type_code)){
            $query->andWhere('c.name LIKE "%'.$this->type_code.'%"');
        }
        $query->andFilterWhere(['a.status' => 1]);
        $query->andFilterWhere(['like', 'a.code', $this->code])
            ->andFilterWhere(['like', 'a.name', $this->name]);

        return $dataProvider;
    }
}
