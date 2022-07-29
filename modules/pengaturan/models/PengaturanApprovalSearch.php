<?php

namespace app\modules\pengaturan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pengaturan\models\PengaturanApproval;

/**
 * PengaturanApprovalSearch represents the model behind the search form of `app\modules\pengaturan\models\PengaturanApproval`.
 */
class PengaturanApprovalSearch extends PengaturanApproval
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_material', 'name', 'slug'], 'safe'],
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
        $query = PengaturanApproval::find()
            ->alias('a')
            ->leftJoin('master_kode b', 'b.code = a.type_material');

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
        if(!empty($this->type_material)){
            $query->andWhere('b.code LIKE "%'.$this->type_material.'%" OR b.name LIKE "%'.$this->type_material.'%"');
        }
        $query->andFilterWhere(['a.status' => 1]);
        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
