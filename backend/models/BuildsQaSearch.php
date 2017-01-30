<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BuildsQa;

/**
 * BuildsQaSearch represents the model behind the search form about `backend\models\BuildsQa`.
 */
class BuildsQaSearch extends BuildsQa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'buiId', 'status', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'safe'],
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
        $query = BuildsQa::find();

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
            'buiId' => $this->buiId,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        $dataProvider->setSort([
            'attributes'=>[
                'id',
                'buiId',
                'description',
                'status',                
                'createdBy'=>[
                    'asc'=>['user.first_name'=>SORT_ASC],
                    'desc'=>['user.first_name'=>SORT_DESC],
                    'label'=>'Created by'
                ],
                'updated_at',
            ],
            'defaultOrder' => ['updated_at'=>SORT_DESC],
        ]);


        return $dataProvider;
    }
}
