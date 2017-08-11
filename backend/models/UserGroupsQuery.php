<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the ActiveQuery class for [[UserGroups]].
 *
 * @see UserGroups
 */
class UserGroupsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UserGroups[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserGroups|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function obtainInheritUsers($root, $level, $lft, $rgt){

        if ($level == 1) {
            $data = Yii::$app->db->createCommand(' SELECT *
                FROM groups g
                LEFT JOIN user_groups ug ON ug.id_group = g.id
                LEFT JOIN user u ON u.id = ug.id_user
                WHERE root = :root AND lvl < :level AND ug.state = 1
                ORDER BY root, lft'
                , array('root' => $root, 'level' => $level) )->queryAll();
        }
        else if ($level >  1){
            $x = $level - 1;
            $data = Yii::$app->db->createCommand('
                SELECT *
                FROM groups g
                LEFT JOIN user_groups ug ON ug.id_group = g.id
                LEFT JOIN user u ON u.id = ug.id_user
                WHERE g.lft < :lft  AND g.rgt > :rgt AND g.root = :root AND g.lvl = :level AND ug.state = 1'
                , array('root' => $root, 'level' => $x, 'rgt' => $rgt, 'lft' => $lft) )->queryAll();

        }
        //echo '<pre>';print_r($data);echo'</pre>'; //die;
        return $data;
    }

}
