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


    public function getOtaProjectsByProject($projectId, $status = 0)
    {
        $projects = ProjectOtaProjects::find()
                    -> where('id_project = :projectId ', [':projectId' => $projectId])
                    -> all();

        $otaProjectsId = array();

        foreach ($projects as $key => $project) {
            array_push($otaProjectsId, $project->id_ota_project);
        }

        $data = OtaProjects::find()
            -> where('deleted = :status ', [':status' => $status])
            -> andWhere(['in', 'id', $otaProjectsId])
            -> orderBY('updated_at DESC')
            -> all();

        return ($data);
    }

    public function getClientAccessOtaProjects($projectId, $id_otaproject, $status = 0)
    {
        $projects = ProjectOtaProjects::find()
            -> where('id_project = :projectId AND id_ota_project = :id_otaproject ', [':projectId' => $projectId, ':id_otaproject' => $id_otaproject])
            -> one();

        //echo 'ACCESS: <pre>'; print_r($projects); echo '</pre>'; die;

        if (!empty($projects))
            return true;
        else
            return false;
        /*
        echo '<pre>'; print_r($projects); echo '</pre>'; //die;

        $otaProjectsId = array();

        foreach ($projects as $key => $project) {
            array_push($otaProjectsId, $project->id_ota_project);
        }

        echo 'eee<pre>'; print_r($otaProjectsId); echo '</pre>'; //die;

        $data = OtaProjects::find()
            -> where('deleted = :status ', [':status' => $status])
            -> andWhere(['in', 'id', $otaProjectsId])
            -> orderBY('updated_at DESC')
            -> one();


        echo 'eee<pre>'; print_r($data); echo '</pre>';
        return ($data);
        */
    }
}
