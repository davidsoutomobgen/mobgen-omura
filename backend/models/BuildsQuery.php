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

    public function getLastBuildsByProjectClient($project_id, $n = 5)
    {
        $query = new Query;
        $query -> select(['id_ota_project'])
            -> from('project_otaprojects')
            -> where('id_project = :project_id ', [':project_id' => $project_id])
            -> orderBY('updated_at DESC')
            -> all();

        $command = $query->createCommand();
        $projects = $command->queryAll();


        if (!isset($projects[0])) {
            return false;
        }

        //echo '<pre>'; print_r($projects); echo '</pre>'; die;
        $ps = array();
        foreach ($projects as $p) {
            $ps[] = (string) '"'. $p['id_ota_project'] . '"';
        }
        $array = implode(",", $ps);

        //print_r($ps); print_r($array); die;
        /*
        $query = new Query;
        $query ->from('builds')
            -> where('buiProIdFK IN ('.$array.') AND buiVisibleClient = :status ', [':status' => 1])
            -> orderBY('updated_at DESC')
            -> limit($n)
            -> all();


        $command = $query->createCommand();
        $builds = $command->queryAll();
        */

        $builds = Builds::find()->from('builds')
            -> where('buiProIdFK IN ('.$array.') AND buiVisibleClient >= :status ', [':status' => 1])
            -> orderBY('updated_at DESC')
            -> limit($n)
            -> all();
        //print_r($builds); die;
        return ($builds);
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

    public function getOtaProjectsByClientUser($userid)
    {
        /*
        User::
        $query = new Query;
        $query -> select(['id_ota_project'])
            -> from('project_otaprojects')
            -> where('id_project = :project_id ', [':project_id' => $project_id])
            -> orderBY('updated_at DESC')
            -> all();

        $command = $query->createCommand();
        $projects = $command->queryAll();
        //print_r(count($data));die;
        return (count($data));
        */
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

    public function activeBuildsType($id = 0)
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds')
            -> where('buiStatus = :status AND buiType = :type', [':status' => 0, ':type' => $id])
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
            -> where('updated_at < :date AND buiFav = :fav AND buiStatus = :status', [':date' => $date, ':fav' => 0, ':status' => 0])
            -> all();

        $command = $query->createCommand();
        $data = $command->queryAll();
        //print_r($data[0]['total']);//die;
        return ($data[0]['total']);
    }

    public function activeBuildsByProject($buiProIdFK)
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds')
            -> where('buiStatus = :status AND buiProIdFK = :buiProIdFK', [':status' => 0, ':buiProIdFK' => $buiProIdFK] )
            -> all();
        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['total']);
    }

    public function favBuildsByProject($buiProIdFK)
    {
        $query = new Query;
        $query -> select(['count(*) as total'])
            -> from('builds')
            -> where('buiStatus = :status  AND buiProIdFK = :buiProIdFK AND buiFav = :fav', [ ':status' => 0, ':buiProIdFK' => $buiProIdFK, ':fav' => 1] )
            -> all();
        $command = $query->createCommand();
        $data = $command->queryAll();
        return ($data[0]['total']);
    }
}
