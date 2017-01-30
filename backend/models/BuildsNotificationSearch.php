<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BuildsNotification;

/**
 * BuildsNotificationSearch represents the model behind the search form about `backend\models\BuildsNotification`.
 */
class BuildsNotificationSearch extends BuildsNotification
{
    public $createdBy;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'buiId', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['email', 'createdBy'], 'safe'],
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
        $query = BuildsNotification::find();
        // add conditions that should always apply here
        $query->select(['builds_notification.*', 'CONCAT (user.first_name," ", user.last_name) as createdBy']);
        $query->leftJoin('user', 'builds_notification.created_by = user.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

         $query->andFilterWhere([
            'id' => $this->id,
            'buiId' => $this->buiId,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);


        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere([
                'or',
                ['like', 'user.first_name', $this->createdBy],
                ['like', 'user.last_name', $this->createdBy]
            ]);

        $dataProvider->setSort([
            'attributes'=>[
                'buiId',
                'email',
                'createdBy'=>[
                    'asc'=>['user.first_name'=>SORT_ASC],
                    'desc'=>['user.first_name'=>SORT_DESC],
                    'label'=>'Sent by'
                ],
                'updated_at',
            ],
            'defaultOrder' => ['updated_at' => SORT_DESC],
        ]);

        return $dataProvider;
    }
}
