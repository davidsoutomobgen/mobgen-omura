<?php

namespace backend\models;

use yii\db\Query;

/**
 * This is the ActiveQuery class for [[Client]].
 *
 * @see Client
 */
class ClientQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Client[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Client|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getOtaProjectsClientByUser($userid)
    {
        $query = new Query;
        $query -> select(['id_project'])
            -> from('client')
            -> where('user = :id_user', [':id_user' => $userid])
            -> one();

        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['id_project']);
    }

}