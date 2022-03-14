<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterSatuan;

/**
 * MasterSatuanSearch represents the model behind the search form of `app\modules\master\models\MasterSatuan`.
 */
class MasterSatuanSearch extends MasterSatuan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_code', 'type_satuan', 'um_1', 'um_2', 'um_3', 'konversi_1', 'konversi_2', 'konversi_3'], 'safe'],
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
        $query = MasterSatuan::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code');

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
        if(!empty($this->type_satuan)){
            $query->andWhere('b.name LIKE "%'.$this->type_satuan.'%"');
        }
        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'um_1', $this->um_1])
            ->andFilterWhere(['like', 'um_2', $this->um_2])
            ->andFilterWhere(['like', 'um_3', $this->um_3])
            ->andFilterWhere(['like', 'konversi_1', $this->konversi_1])
            ->andFilterWhere(['like', 'konversi_2', $this->konversi_2])
            ->andFilterWhere(['like', 'konversi_3', $this->konversi_3]);

        return $dataProvider;
    }
}
