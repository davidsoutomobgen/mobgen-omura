<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use common\models\User;

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

        $query = new Query;
        $query->select('mobgenners.*, user.role_id, user.username')
            ->from('mobgenners')
            ->innerJoin('user', 'user.id = mobgenners.user')
            ->where('active = :active', [':active' => 1]);

        $roleId = User::getUserIdRole();

        if ($roleId != Yii::$app->params['ADMIN_ROLE']) {
            $query->andWhere('user.role_id != ' . Yii::$app->params['ADMIN_ROLE']);
        }

        $command = $query->createCommand();
        $rows = $command->queryAll();
        return $this->populate($rows);
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
        $query->select(['count(*) as total'])
            ->from('mobgenners')
            ->innerJoin('user', 'user.id = mobgenners.user')
            ->where('active = :active', [':active' => 1]);

        $roleId = User::getUserIdRole();

        if ($roleId != Yii::$app->params['ADMIN_ROLE']) {
            $query->andWhere('user.role_id != ' . Yii::$app->params['ADMIN_ROLE']);
        }

        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['total']);
    }

}
