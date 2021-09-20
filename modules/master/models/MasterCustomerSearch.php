<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterPerson;

/**
 * MasterCustomerSearch represents the model behind the search form of `app\modules\master\models\MasterPerson`.
 */
class MasterCustomerSearch extends MasterPerson
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'name', 'address', 'phone_1', 'created_at', 'updated_at'], 'safe'],
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
        $query = MasterPerson::find();

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
        $query->where(['type_user' => \Yii::$app->params['TYPE_CUSTOMER'], 'status'=>1]);
        if(!empty($this->created_at)){
            $t1 = strtotime($this->created_at);
			$t2 = strtotime("+1 days", $t1);
			$query->andWhere('created_at >='.$t1.' and created_at <'.$t2);
        }
        if(!empty($this->updated_at)){
            $t1 = strtotime($this->updated_at);
			$t2 = strtotime("+1 days", $t1);
			$query->andWhere('updated_at >='.$t1.' and updated_at <'.$t2);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone_1', $this->phone_1])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
