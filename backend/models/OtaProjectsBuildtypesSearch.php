<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OtaProjectsBuildtypes;

/**
 * OtaProjectsBuildtypesSearch represents the model behind the search form about `backend\models\OtaProjectsBuildtypes`.
 */
class OtaProjectsBuildtypesSearch extends OtaProjectsBuildtypes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_ota_project', 'id_ota_buildtypes', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = OtaProjectsBuildtypes::find();

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
            'id' => $this->id,
            'id_ota_project' => $this->id_ota_project,
            'id_ota_buildtypes' => $this->id_ota_buildtypes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
