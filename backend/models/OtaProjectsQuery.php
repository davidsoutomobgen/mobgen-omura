<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[OtaProjects]].
 *
 * @see OtaProjects
 */
class OtaProjectsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return OtaProjects[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OtaProjects|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function getLastProjects($status = 0, $n = 5)
    {

        $data = OtaProjects::find()
            -> where('deleted = :status ', [':status' => $status])
            -> orderBY('updated_at DESC')
            -> limit($n)
            -> all();

        return ($data);
    }
}
