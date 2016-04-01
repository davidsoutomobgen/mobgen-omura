<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Builds]].
 *
 * @see Builds
 */
class BuildsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Builds[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Builds|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
