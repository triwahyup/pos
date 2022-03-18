<?php

namespace app\modules\purchasing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\purchasing\models\PurchaseInternal;

/**
 * PurchaseInternalSearch represents the model behind the search form of `app\modules\purchasing\models\PurchaseInternal`.
 */
class PurchaseInternalSearch extends PurchaseInternal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['no_po', 'tgl_po', 'keterangan'], 'safe'],
            [['total_order'], 'number'],
            [['user_id', 'user_request', 'status', 'status_approval', 'created_at', 'updated_at'], 'integer'],
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
        $query = PurchaseInternal::find();

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
        $query->andFilterWhere([
            'total_order' => $this->total_order,
            'user_id' => $this->user_id,
            'user_request' => $this->user_request,
            'status' => 1,
            'status_approval' => $this->status_approval,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if(!empty($this->tgl_po)){
            $query->andFilterWhere(['tgl_po' => date('Y-m-d', strtotime($this->tgl_po))]);
        }

        $query->andFilterWhere(['like', 'no_po', $this->no_po])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
