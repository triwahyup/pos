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
            [['item_code'], 'safe'],
            [['qty_in', 'qty_out', 'qty_retur', 'onhand', 'onsales'], 'integer'],
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
            ->leftJoin('master_material_item b', 'b.code = a.item_code');

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
        $query->andFilterWhere(['like', 'qty_in', $this->qty_in])
            ->andFilterWhere(['like', 'qty_out', $this->qty_out])
            ->andFilterWhere(['like', 'qty_retur', $this->qty_retur])
            ->andFilterWhere(['like', 'onhand', $this->onhand])
            ->andFilterWhere(['like', 'onsales', $this->onsales]);

        return $dataProvider;
    }
}