<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Project;

/**
 * ProjectSearch represents the model behind the search form about `backend\models\Project`.
 */
class ProjectSearch extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'internal', 'created_at', 'updated_at', 'deleted'], 'integer'],
            [['name', 'alias', 'logo', 'description', 'additional_information', 'onboarding_document'], 'safe'],
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
    public function searchPermission($params)
    {

        /*
        Obtain projects with permission to view
        SELECT p.* FROM `project` p LEFT OUTER JOIN `permissions_users` ON permissions_users.id_permission = p.permission_view WHERE (`state`=1) AND (`id_user`=10)
        UNION
        SELECT p.* FROM `project` p WHERE p.permission_view IN (9, 10, 11, 12);
        */
        $this->load($params);

        $query = Project::find();
        $query->join('LEFT OUTER JOIN', 'permissions_users', 'permissions_users.id_permission = project.permission_view');
        $query->andFilterWhere([
            'id' => $this->id,
            'actives' => $this->active,
            'internal' => $this->internal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $this->deleted,
            'state' => 1,
            'id_user' => Yii::$app->user->identity->id
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'additional_information', $this->additional_information])
            ->andFilterWhere(['like', 'onboarding_document', $this->onboarding_document]);


        $parent = Permissions::find()->getPermissionsUserByGroup(Yii::$app->user->identity->id);

        $query2 = Project::find();
        $query2->andFilterWhere(['IN', 'permission_view', $parent]);
        $query2->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'additional_information', $this->additional_information])
            ->andFilterWhere(['like', 'onboarding_document', $this->onboarding_document]);

        $query->union($query2, false);//false is UNION, true is UNION ALL
        $sql = $query->createCommand()->getRawSql();
        $sql.= ' ORDER BY id DESC';


        $queryUnion = Project::findBySql($sql);


        $dataProvider = new ActiveDataProvider([
            //'query' => $query,
            'query' => $queryUnion,
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function search($params)
    {
        $query = Project::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'internal' => $this->internal,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $this->deleted,
        ]);

        //$query->andFilterWhere();

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'additional_information', $this->additional_information])
            ->andFilterWhere(['like', 'onboarding_document', $this->onboarding_document]);

        return $dataProvider;
    }

}
