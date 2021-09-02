<?php

namespace app\modules\master\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
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
            [['code', 'name', 'address', 'phone_1', 'phone_2', 'email', 'fax', 'keterangan', 'masuk', 'keluar', 'tgl_jatuh_tempo', 'group_supplier_code'], 'safe'],
            [['provinsi_id', 'kabupaten_id', 'kecamatan_id', 'kelurahan_id', 'kode_pos', 'type_user', 'status', 'created_at', 'updated_at'], 'integer'],
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
        $query = MasterPerson::find()->where(['type_user'=>2]);

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
            'provinsi_id' => $this->provinsi_id,
            'kabupaten_id' => $this->kabupaten_id,
            'kecamatan_id' => $this->kecamatan_id,
            'kelurahan_id' => $this->kelurahan_id,
            'kode_pos' => $this->kode_pos,
            'type_user' => $this->type_user,
            'masuk' => $this->masuk,
            'keluar' => $this->keluar,
            'tgl_jatuh_tempo' => $this->tgl_jatuh_tempo,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone_1', $this->phone_1])
            ->andFilterWhere(['like', 'phone_2', $this->phone_2])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'group_supplier_code', $this->group_supplier_code]);

        return $dataProvider;
    }
}
