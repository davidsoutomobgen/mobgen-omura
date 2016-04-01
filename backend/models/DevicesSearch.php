<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Devices;

/**
 * DevicesSearch represents the model behind the search form about `backend\models\Devices`.
 */
class DevicesSearch extends Devices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'delete'], 'integer'],
            [['label_1', 'label_2', 'brand', 'type', 'technical_details', 'remarks', 'office', 'used_by', 'information_by'], 'safe'],
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
        $query = Devices::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'delete' => $this->delete,
        ]);

        $query->andFilterWhere(['like', 'label_1', $this->label_1])
            ->andFilterWhere(['like', 'label_2', $this->label_2])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'technical_details', $this->technical_details])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'office', $this->office])
            ->andFilterWhere(['like', 'used_by', $this->used_by])
            ->andFilterWhere(['like', 'information_by', $this->information_by]);

        return $dataProvider;
    }
}
