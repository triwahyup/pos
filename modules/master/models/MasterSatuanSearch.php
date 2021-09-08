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
            [['code', 'name', 'type', 'keterangan', 'created_at', 'updated_at'], 'safe'],
            [['qty'], 'number'],
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
            ->leftJoin('master_kode b', 'b.code = a.type');

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

        $query->where(['a.status'=>1]);
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
        if(!empty($this->type)){
            $query->andWhere('b.name LIKE "%'.$this->type.'%"');
        }
        $query->andFilterWhere(['qty' => $this->qty]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
