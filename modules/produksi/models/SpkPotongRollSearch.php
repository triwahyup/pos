<?php

namespace app\modules\produksi\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\produksi\models\SpkPotongRoll;

/**
 * SpkPotongRollSearch represents the model behind the search form of `app\modules\produksi\models\SpkPotongRoll`.
 */
class SpkPotongRollSearch extends SpkPotongRoll
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'item_code', 'supplier_code', 'type_code', 'material_code', 'satuan_code', 'post', 'date'], 'safe'],
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
        $query = SpkPotongRoll::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->leftJoin('master_satuan c', 'c.code = a.satuan_code')
            ->leftJoin('master_person d', 'd.code = a.supplier_code')
            ->leftJoin('master_material e', 'e.code = a.item_code');

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
        if(!empty($this->date)){
            $query->andFilterWhere(['date' => date('Y-m-d', strtotime($this->date))]);
        }
        if(!empty($this->type_code)){
            $query->andWhere('b.code LIKE "%'.$this->type_code.'%" OR b.name LIKE "%'.$this->type_code.'%"');
        }
        if(!empty($this->material_code)){
            $query->andWhere('b.code LIKE "%'.$this->material_code.'%" OR b.name LIKE "%'.$this->material_code.'%"');
        }
        if(!empty($this->satuan_code)){
            $query->andWhere('c.code LIKE "%'.$this->satuan_code.'%" OR c.name LIKE "%'.$this->satuan_code.'%"');
        }
        if(!empty($this->supplier_code)){
            $query->andWhere('d.code LIKE "%'.$this->supplier_code.'%" OR d.name LIKE "%'.$this->supplier_code.'%"');
        }
        if(!empty($this->item_code)){
            $query->andWhere('e.code LIKE "%'.$this->item_code.'%" OR e.name LIKE "%'.$this->item_code.'%"');
        }

        $query->andFilterWhere(['like', 'code', $this->code]);
        return $dataProvider;
    }
}
