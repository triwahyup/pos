<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterMaterialItem;

class MasterMaterialItemSearch extends MasterMaterialItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'type_code', 'satuan_code', 'group_material_code', 'group_supplier_code', 'material_code'], 'safe'],
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
        $query = MasterMaterialItem::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_code')
            ->leftJoin('master_satuan c', 'c.code = a.satuan_code')
            ->leftJoin('master_group_material d', 'd.code = a.group_material_code')
            ->leftJoin('master_group_supplier e', 'e.code = a.group_supplier_code')
            ->leftJoin('master_material f', 'f.code = a.material_code');

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
        if(!empty($this->type_code)){
            $query->andWhere('b.name LIKE "%'.$this->type_code.'%"');
        }
        if(!empty($this->satuan_code)){
            $query->andWhere('c.name LIKE "%'.$this->satuan_code.'%"');
        }
        if(!empty($this->group_material_code)){
            $query->andWhere('d.name LIKE "%'.$this->group_material_code.'%"');
        }
        if(!empty($this->group_supplier_code)){
            $query->andWhere('e.name LIKE "%'.$this->group_supplier_code.'%"');
        }
        if(!empty($this->material_code)){
            $query->andWhere('f.name LIKE "%'.$this->material_code.'%"');
        }
        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.code', $this->code]);

        return $dataProvider;
    }
}