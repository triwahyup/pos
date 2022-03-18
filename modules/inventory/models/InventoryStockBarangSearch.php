<?php

namespace app\modules\inventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\InventoryStockBarang;

class InventoryStockBarangSearch extends InventoryStockBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_code', 'supplier_code'], 'safe'],
            [['stock'], 'integer'],
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
        $query = InventoryStockBarang::find()
            ->alias('a')
            ->leftJoin('master_barang b', 'b.code = a.barang_code')
            ->leftJoin('master_person c', 'c.code = a.supplier_code');

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
        if(!empty($this->barang_code)){
            $query->andWhere('code LIKE "%'.$this->barang_code.'%" OR name LIKE "%'.$this->barang_code.'%"');
        }
        if(!empty($this->supplier_code)){
            $query->andWhere('code LIKE "%'.$this->supplier_code.'%" OR name LIKE "%'.$this->supplier_code.'%"');
        }
        $query->andFilterWhere(['like', 'stock', $this->stock]);

        return $dataProvider;
    }
}