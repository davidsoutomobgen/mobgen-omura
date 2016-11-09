<?php

namespace backend\models;

use yii\db\Query;

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

    public function getBuildsByUser($userid)
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds')
            -> where('created_by = :id_user', [':id_user' => $userid])
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        //print_r($data[0]['total']);die;
        return ($data[0]['total']);
    }

    public function getLastBuildsByUser($userid, $status = 0, $n = 5)
    {
        $data = Builds::find()
            -> where('created_by = :id_user AND buiStatus = :status ', [':id_user' => $userid,  ':status' => $status])
            -> orderBY('updated_at DESC')
            -> limit($n)
            -> all();
        return ($data);
    }

    public function getLastBuilds($status = 0,  $n = 5)
    {

        $data = Builds::find()
            -> where('buiStatus = :status ', [':status' => $status])
            -> orderBY('updated_at DESC')
            -> limit($n)
            -> all();

        return ($data);
    }

    public function getOtaProjectsByUser($userid)
    {
        $query = new Query;
        $query -> select(['buiId'])
            -> from('builds')
            -> where('created_by = :id_user', [':id_user' => $userid])
            -> groupBy('buiProIdFK')
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        //print_r(count($data));die;
        return (count($data));
    }

    public function activeBuilds()
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds')
            -> where('buiStatus = :status', [':status' => 0])
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['total']);
    }

    public function countRemoveBuilds($date)
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds')
            -> where('updated_at < :date AND buiFav = :fav', [':date' => $date, ':fav' => 0])
            //-> where('updated_at < :date', [':date' => $date])
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        //print_r($data[0]['total']);//die;
        return ($data[0]['total']);
    }
}
