<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PermissionsUsers;
use backend\models\Permissions;

/**
 * PermissionsUsersSearch represents the model behind the search form about `backend\models\PermissionsUsers`.
 */
class PermissionsUsersSearch extends PermissionsUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_permission', 'id_user', 'state', 'created_at', 'updated_at'], 'integer'],
            [['id_permission', 'id_user', 'state', 'idPermissions.label'], 'safe'],
        ];
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idPermissions.label']);
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
        $query = PermissionsUsers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['idPermissions.label'] = [
            'asc' => ['idPermissions.label' => SORT_ASC],
            'desc' => ['idPermissions.label' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['idPermissions']);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_permission' => $this->id_permission,
            'id_user' => $this->id_user,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['LIKE', 'idPermissions.label', $this->getAttribute('idPermissions.label')]);

        return $dataProvider;
    }
}
