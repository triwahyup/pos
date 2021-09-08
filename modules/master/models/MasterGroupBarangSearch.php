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
            [['name', 'created_at', 'updated_at', 'acc_persediaan_code', 'acc_penjualan_code', 'acc_hpp_code'], 'safe'],
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
        $query = MasterGroupBarang::find()
            ->alias('a')
            ->leftJoin('master_accounts_detail b', 
                '(b.accounts_code = a.acc_persediaan_code and b.urutan = a.acc_persediaan_urutan) 
                OR (b.accounts_code = a.acc_penjualan_code and b.urutan = a.acc_penjualan_urutan)
                OR (b.accounts_code = a.acc_hpp_code and b.urutan = a.acc_hpp_urutan)');

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

        if(!empty($this->acc_persediaan_code)){
            $query->andWhere('b.name LIKE "%'.$this->acc_persediaan_code.'%"');
        }
        if(!empty($this->acc_penjualan_code)){
            $query->andWhere('b.name LIKE "%'.$this->acc_penjualan_code.'%"');
        }
        if(!empty($this->acc_hpp_code)){
            $query->andWhere('b.name LIKE "%'.$this->acc_hpp_code.'%"');
        }

        $query->andFilterWhere(['like', 'a.name', $this->name]);

        return $dataProvider;
    }
}
