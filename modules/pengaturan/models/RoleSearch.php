<?php

namespace app\modules\pengaturan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\master\models\MasterKode;

/**
 * RoleSearch represents the model behind the search form of `app\modules\master\models\MasterKode`.
 */
class RoleSearch extends MasterKode
{
    public $menu;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name',  'menu'], 'safe'],
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
        $query = MasterKode::find();

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
        
        $query->where(['type'=>\Yii::$app->params['TYPE_USER'], 'status'=>1])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type]);

        if(!empty($this->menu)){
            $childs = AuthItemChild::find()
                ->select(['parent'])
                ->leftJoin('pengaturan_menu', 'auth_item_child.child = pengaturan_menu.slug')
                ->where("child LIKE '%".$this->menu."%'")
                ->groupBy('parent')
                ->column();
            foreach($childs as $val){
                $queryMenu = " OR (code='".$val."')";
            }
            $query->andWhere($queryMenu);
        }
        return $dataProvider;
    }
}
