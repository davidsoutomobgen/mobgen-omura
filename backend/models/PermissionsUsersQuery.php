<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[PermissionsUsers]].
 *
 * @see PermissionsUsers
 */
class PermissionsUsersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PermissionsUsers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PermissionsUsers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
