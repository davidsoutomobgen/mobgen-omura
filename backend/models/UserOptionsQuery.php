<?php

namespace backend\models;

use yii\db\Query;

/**
 * This is the ActiveQuery class for [[UserOptions]].
 *
 * @see UserOptions
 */
class UserOptionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UserOptions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserOptions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getVariable($userid, $variable){

        $query = new Query;
        $query	->select(['user_options.value', 'options.variable'])
                ->from('user_options')
                ->leftJoin('options', 'options.id = user_options.id_option')
                ->where('id_user = :userid', [':userid' => $userid])
                ->andWhere('variable = :variable', [':variable' => $variable])
                ->limit(1);

        $command = $query->createCommand();
        $data = $command->queryAll();

        return $data[0];
    }

    public function getUserOptionsByUserId($userid)
    {
        $query = new Query;
        $query -> select(['options.variable', 'options.type', 'user_options.value'])
               -> from('user_options')
               -> leftJoin('options', 'options.id = user_options.id_option')
               -> where('id_user = :id_user', [':id_user' => $userid])
               -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();

        return ($data);
    }
}