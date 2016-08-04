<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OtaProjects;

/**
 * OtaProjectsSearch represents the model behind the search form about `backend\models\OtaProjects`.
 */
class OtaProjectsSearch extends OtaProjects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_project', 'id_ota_template', 'created_at', 'updated_at'], 'integer'],
            [['name', 'safename', 'proCreated', 'proModified', 'proHash', 'proAPIKey', 'proAPIBuildKey', 'proBuildTypes', 'default_notify_email', 'proDevUrl1', 'proDevUrl2', 'proDevUrl3', 'proDevUrl4', 'proAltUrl1', 'proAltUrl2', 'proAltUrl3', 'proAltUrl4', 'proProdUrl1', 'proProdUrl2', 'proProdUrl3', 'proProdUrl4'], 'safe'],
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
        $query = OtaProjects::find();

        // add conditions that should always apply here
        /*
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        */
        $dataProvider = new ActiveDataProvider([
           'pagination'=>array(
                    'pageSize'=>20,
            ),
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]]
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
            'proCreated' => $this->proCreated,
            'proModified' => $this->proModified,
            'id_project' => $this->id_project,
            'id_ota_template' => $this->id_ota_template,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'safename', $this->safename])
            ->andFilterWhere(['like', 'proHash', $this->proHash])
            ->andFilterWhere(['like', 'proAPIKey', $this->proAPIKey])
            ->andFilterWhere(['like', 'proAPIBuildKey', $this->proAPIBuildKey])
            ->andFilterWhere(['like', 'proBuildTypes', $this->proBuildTypes])
            ->andFilterWhere(['like', 'default_notify_email', $this->default_notify_email])
            ->andFilterWhere(['like', 'proDevUrl1', $this->proDevUrl1])
            ->andFilterWhere(['like', 'proDevUrl2', $this->proDevUrl2])
            ->andFilterWhere(['like', 'proDevUrl3', $this->proDevUrl3])
            ->andFilterWhere(['like', 'proDevUrl4', $this->proDevUrl4])
            ->andFilterWhere(['like', 'proAltUrl1', $this->proAltUrl1])
            ->andFilterWhere(['like', 'proAltUrl2', $this->proAltUrl2])
            ->andFilterWhere(['like', 'proAltUrl3', $this->proAltUrl3])
            ->andFilterWhere(['like', 'proAltUrl4', $this->proAltUrl4])
            ->andFilterWhere(['like', 'proProdUrl1', $this->proProdUrl1])
            ->andFilterWhere(['like', 'proProdUrl2', $this->proProdUrl2])
            ->andFilterWhere(['like', 'proProdUrl3', $this->proProdUrl3])
            ->andFilterWhere(['like', 'proProdUrl4', $this->proProdUrl4]);

        return $dataProvider;
    }
}
