<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\commands\Konstanta;
use app\modules\master\models\MasterPerson;

/**
 * MasterSupplierSearch represents the model behind the search form of `app\modules\master\models\MasterPerson`.
 */
class MasterSupplierSearch extends MasterPerson
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'address', 'phone_1', 'email', 'tgl_jatuh_tempo', 'group_supplier_code'], 'safe'],
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

        $query->where(['type_user'=>Konstanta::TYPE_SUPPLIER, 'status'=>1]);
        // grid filtering conditions
        $query->andFilterWhere([
            'tgl_jatuh_tempo' => $this->tgl_jatuh_tempo,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone_1', $this->phone_1])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'group_supplier_code', $this->group_supplier_code]);

        return $dataProvider;
    }
}
