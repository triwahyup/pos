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
            [['code', 'name', 'type', 'jenis'], 'safe'],
            [['panjang', 'lebar', 'gram'], 'number'],
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
        $query = MasterBarang::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type')
            ->leftJoin('master_jenis_barang c', 'c.code = a.jenis');

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
        $query->andFilterWhere([
            'panjang' => $this->panjang,
            'lebar' => $this->lebar,
            'gram' => $this->gram
        ]);

        if(!empty($this->type)){
            $query->andWhere('b.name LIKE "%'.$this->type.'%"');
        }
        if(!empty($this->jenis)){
            $query->andWhere('c.name LIKE "%'.$this->jenis.'%"');
        }
        $query->andFilterWhere(['like', 'a.name', $this->name]);

        return $dataProvider;
    }
}
