<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[PermissionsGroups]].
 *
 * @see PermissionsGroups
 */
class PermissionsGroupsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PermissionsGroups[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PermissionsGroups|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
