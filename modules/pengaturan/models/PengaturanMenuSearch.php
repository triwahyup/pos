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
            [['id', 'name', 'slug', 'link', 'icon'], 'safe'],
            [['level', 'parent_id', 'urutan', 'position', 'created_at', 'updated_at'], 'integer'],
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
            'link' => $this->link,
            'parent_id' => $this->parent_id,
            'urutan' => $this->urutan,
            'position' => $this->position,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}
