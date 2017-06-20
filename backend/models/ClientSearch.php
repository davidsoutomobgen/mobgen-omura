<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Client;

/**
 * ClientSearch represents the model behind the search form about `backend\models\Client`.
 */
class ClientSearch extends Client
{

    public $projectName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_project', 'active', 'user', 'created_at', 'updated_at', 'deleted'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'company', 'job_title', 'image', 'projectName'], 'safe'],
            //[['projectName'], 'safe'],

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
        $query = Client::find();

        $query->select(['client.*', 'project.name as projectName']);
        $query->leftJoin('project', 'project.id = client.id_project');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //$query->with('project');

        $query->andFilterWhere([
            'id' => $this->id,
            'id_project' => $this->id_project,
            'active' => $this->active,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'client.first_name', $this->first_name])
            ->andFilterWhere(['like', 'client.last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'project.name', $this->projectName]);

        //->andFilterWhere(['like', 'projectNames', $this->projectName]);

        $dataProvider->setSort([
            'attributes'=>[
                //'id',
                'first_name',
                'last_name',
                'projectName'=>[
                    'asc'=>['project.name'=>SORT_ASC],
                    'desc'=>['project.name'=>SORT_DESC],
                    'label'=>'Project'
                ],
                'email',
                'phone',
            ],
        ]);

        //$query->andFilterWhere(['projectName'=>$this->projectName]);


        return $dataProvider;
    }
}
