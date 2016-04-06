<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Builds;

/**
 * BuildsSearch represents the model behind the search form about `backend\models\Builds`.
 */
class BuildsSearch extends Builds
{
    public $createdBy;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buiId', 'buiProIdFK', 'buiCerIdFK', 'buiType', 'buiVisibleClient', 'buiDeviceOS', 'buiLimitedUDID', 'buiFav', 'buiSendEmail', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['buiName', 'buiSafename', 'buiCreated', 'buiModified', 'buiTemplate', 'buiFile', 'buiVersion', 'buiBuildNum', 'buiChangeLog', 'buiBuildType', 'buiApple', 'buiSVN', 'buiFeedUrl', 'buiBundleIdentifier', 'buiHash', 'createdBy'], 'safe'],
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
        $query = Builds::find();
        // add conditions that should always apply here
        $query->select(['builds.*', 'CONCAT (user.first_name," ", user.last_name) as createdBy']);
        $query->leftJoin('user', 'builds.created_by = user.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ["buiFav"=>SORT_DESC, 'buiModified'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'buiId' => $this->buiId,
            'buiCreated' => $this->buiCreated,
            'buiModified' => $this->buiModified,
            'buiProIdFK' => $this->buiProIdFK,
            'buiCerIdFK' => $this->buiCerIdFK,
            'buiType' => $this->buiType,
            'buiVisibleClient' => $this->buiVisibleClient,
            'buiDeviceOS' => $this->buiDeviceOS,
            'buiLimitedUDID' => $this->buiLimitedUDID,
            'buiFav' => $this->buiFav,
            'buiSendEmail' => $this->buiSendEmail,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'buiName', $this->buiName])
            ->andFilterWhere(['like', 'buiSafename', $this->buiSafename])
            ->andFilterWhere(['like', 'buiTemplate', $this->buiTemplate])
            ->andFilterWhere(['like', 'buiFile', $this->buiFile])
            ->andFilterWhere(['like', 'buiVersion', $this->buiVersion])
            ->andFilterWhere(['like', 'buiBuildNum', $this->buiBuildNum])
            ->andFilterWhere(['like', 'buiChangeLog', $this->buiChangeLog])
            ->andFilterWhere(['like', 'buiBuildType', $this->buiBuildType])
            ->andFilterWhere(['like', 'buiApple', $this->buiApple])
            ->andFilterWhere(['like', 'buiSVN', $this->buiSVN])
            ->andFilterWhere(['like', 'buiFeedUrl', $this->buiFeedUrl])
            ->andFilterWhere(['like', 'buiBundleIdentifier', $this->buiBundleIdentifier])
            ->andFilterWhere(['like', 'buiHash', $this->buiHash])
            ->andFilterWhere([
                'or',
                ['like', 'user.first_name', $this->createdBy],
                ['like', 'user.last_name', $this->createdBy]
            ]);

            //->andFilterWhere(['like', 'buiFav', $this->buiFav]);

        $dataProvider->setSort([
            'attributes'=>[
                'buiType',
                'buiName',
                'buiVersion',
                'buiHash',
                'createdBy'=>[
                    'asc'=>['user.first_name'=>SORT_ASC],
                    'desc'=>['user.first_name'=>SORT_DESC],
                    'label'=>'Created by'
                ],
                'updated_at',
                'buiFav',
            ],
            'defaultOrder' => ['buiFav'=>SORT_DESC, 'updated_at'=>SORT_DESC],
        ]);

        return $dataProvider;
    }
}
