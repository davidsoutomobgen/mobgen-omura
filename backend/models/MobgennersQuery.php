<?php

namespace backend\models;

use yii\db\Query;

/**
 * This is the ActiveQuery class for [[Options]].
 *
 * @see Options
 */
class MobgennersQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Options[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Options|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function activeMobgenners()
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('mobgenners')
            -> where('active = :active', [':active' => 1])
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['total']);
    }
}