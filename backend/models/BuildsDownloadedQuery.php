<?php

namespace backend\models;

use yii\db\Query;

/**
 * This is the ActiveQuery class for [[BuildsDownloaded]].
 *
 * @see BuildsDownloaded
 */
class BuildsDownloadedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return BuildsDownloaded[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BuildsDownloaded|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function totalDownloads()
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds_downloaded')
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['total']);
    }
}
