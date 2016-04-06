<?php

namespace backend\models;

use yii\db\Query;

/**
 * This is the ActiveQuery class for [[Options]].
 *
 * @see Options
 */
class OptionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

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
}