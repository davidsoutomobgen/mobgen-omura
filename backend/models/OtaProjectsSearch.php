<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OtaProjects;
use backend\models\UserOptions;

/**
 * OtaProjectsSearch represents the model behind the search form about `backend\models\OtaProjects`.
 */
class OtaProjectsSearch extends OtaProjects
{
    public $pagesize;
    public $numBuilds;
    public $numFavs;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_project', 'id_ota_template', 'created_at', 'updated_at', 'numBuilds', 'numFavs'], 'integer'],
            [['name', 'safename', 'numBuilds', 'numFavs', 'proCreated', 'proModified', 'proHash', 'proAPIKey', 'proAPIBuildKey', 'proBuildTypes', 'default_notify_email'], 'safe'],
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
        /** pagesize **/
        $option = UserOptions::find()->getVariable(Yii::$app->user->id, 'pages_table_otaprojects');
        $pagesize = (int) $option['value'];
        /**************/
        $query = OtaProjects::find();

        // add conditions that should always apply here
        /*
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pagesize,
            ],
        */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]],
	    'pagination' => [
                'pageSize' => $pagesize,
            ],
        ]);
/*
        $dataProvider->setSort([
            'attributes' => [
                'name',
                'proHash',
                'proAPIKey',
                'numBuilds' => [
                    'asc' => ['numBuilds' => SORT_ASC],
                    'desc' => ['numBuilds' => SORT_DESC],
                    'label' => 'numBuilds',
                    'default' => SORT_ASC
                ],
            ]
        ]);
        */

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'proCreated' => $this->proCreated,
            'proModified' => $this->proModified,
            'id_project' => $this->id_project,
            'id_ota_template' => $this->id_ota_template,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'numBuilds' => $this->numBuilds,
            //'numFavs' => $this->numFavs,
        ]);

        //$query->andFilterWhere(['=', 'numBuilds', Builds::getA());


        $query->andFilterWhere(['like', 'LOWER(name)', strtolower($this->name)])
            ->andFilterWhere(['like', 'LOWER(safename)', strtolower($this->safename)])
            ->andFilterWhere(['like', 'proHash', $this->proHash])
            ->andFilterWhere(['like', 'proAPIKey', $this->proAPIKey])
            ->andFilterWhere(['like', 'proAPIBuildKey', $this->proAPIBuildKey])
            ->andFilterWhere(['like', 'proBuildTypes', $this->proBuildTypes])
            ->andFilterWhere(['like', 'default_notify_email', $this->default_notify_email]);

        return $dataProvider;
    }
}
