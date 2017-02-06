<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Mobgenners;
use common\models\User;

/**
 * MobgennersSearch represents the model behind the search form about `backend\models\Mobgenners`.
 */
class MobgennersSearch extends Mobgenners
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active', 'user', 'created_at', 'updated_at', 'deleted'], 'integer'],
            [['first_name', 'last_name', 'email', 'phone', 'skype', 'job_title', 'image', 'roleName'], 'safe'],
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
        $query = Mobgenners::find();
        $query->joinWith('user0');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $roleId = null;

        if ($this->roleName) {
            $items[1] = 'ADMIN';
            $items[10] = 'DEVELOPER';
            $items[11] = 'QA';
            $items[12] = 'LEAD';

            foreach ($items as $key => $item) {
                if (strpos($item, strtoupper($this->roleName)) !== false) {
                    $roleId = $key;
                    break;
                }
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'active' => 1,
            'user' => $this->user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $this->deleted,
            'user.role_id' => $roleId
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'skype', $this->skype])
            ->andFilterWhere(['like', 'job_title', $this->job_title]);

            $roleId = User::getUserIdRole();

            if ($roleId != 1) {
                $query->andFilterWhere(['!=', 'user.role_id', 1]);
            }


        return $dataProvider;
    }

}
