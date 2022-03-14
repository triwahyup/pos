<?php

namespace app\modules\inventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\InventoryStockItem;

class InventoryStockItemSearch extends InventoryStockItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_code', 'supplier_code'], 'safe'],
            [['onhand', 'onsales'], 'integer'],
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
        $query = InventoryStockItem::find()
            ->alias('a')
            ->leftJoin('master_material b', 'b.code = a.item_code')
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
        if(!empty($this->item_code)){
            $query->andWhere('code LIKE "%'.$this->item_code.'%" OR name LIKE "%'.$this->item_code.'%"');
        }
        if(!empty($this->supplier_code)){
            $query->andWhere('code LIKE "%'.$this->supplier_code.'%" OR name LIKE "%'.$this->supplier_code.'%"');
        }
        $query->andFilterWhere(['like', 'onhand', $this->onhand])
        ->andFilterWhere(['like', 'onsales', $this->onsales]);

        return $dataProvider;
    }
}