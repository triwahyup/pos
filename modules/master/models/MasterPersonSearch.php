<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterPerson;

/**
 * MasterPersonSearch represents the model behind the search form of `app\modules\master\models\MasterPerson`.
 */
class MasterPersonSearch extends MasterPerson
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'name', 'address', 'phone_1', 'created_at', 'updated_at', 'contact_person', 'term_in', 'type_user'], 'safe'],
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
        $query = MasterPerson::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.value = a.type_user');

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
        if(!empty($this->type_user)){
            $query->andWhere('b.name LIKE "%'.$this->type_user.'%"');
        }
        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'phone_1', $this->phone_1])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'term_in', $this->term_in]);

        return $dataProvider;
    }
}
