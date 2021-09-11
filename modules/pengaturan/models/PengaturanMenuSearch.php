<?php

namespace app\modules\pengaturan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\pengaturan\models\PengaturanMenu;

/**
 * PengaturanMenuSearch represents the model behind the search form of `app\modules\pengaturan\models\PengaturanMenu`.
 */
class PengaturanMenuSearch extends PengaturanMenu
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'link', 'parent_code', 'type_code', 'level', 'urutan'], 'safe'],
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
        $query = PengaturanMenu::find();

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
            'level' => $this->level,
            'urutan' => $this->urutan,
        ]);

        $query->where(['status' => 1])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'parent_code', $this->parent_code])
            ->andFilterWhere(['like', 'type_code', $this->type_code]);

        return $dataProvider;
    }
}
