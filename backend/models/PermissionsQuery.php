<?php

namespace backend\models;

use Yii;
use backend\models\Permissions;
use common\models\User;

/**
 * This is the ActiveQuery class for [[Permissions]].
 *
 * @see Permissions
 */
class PermissionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Permissions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Permissions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function hasPermission($permission){

        $data = array();
        //echo Yii::$app->user->identity->id; die;
        if (Yii::$app->user->identity->id == 1) $data[0]['state'] = 1;
        else {
            //Check if have permissions as User
            $data = Yii::$app->db->createCommand('SELECT state FROM permissions_users pu
                                                    LEFT JOIN permissions p ON pu.id_permission = p.id
                                                    WHERE pu.id_user = :id_user AND p.name = :permission'
                , array('id_user' => Yii::$app->user->identity->id, 'permission' => $permission) )->queryAll();

            $perm = Permissions::findOne(['name' => $permission]);
 
            if (empty($data[0]['state'])) {

                //If don't have permission as User check if have permission by Group
                //echo $permission;
                $data = '';
                if (isset($perm->id)) {                                
                    $data = Yii::$app->db->createCommand('SELECT *
                            FROM permissions_groups pg
                            LEFT JOIN groups g ON g.id = pg.id_group
                            LEFT JOIN user_groups ug ON ug.id_group=g.id
                            WHERE pg.id_permission =:id_permission AND ug.id_user = '. Yii::$app->user->identity->id
                            , array('id_permission' => $perm->id))->queryAll();
                }

                //echo  Yii::$app->user->identity->id;die;
                //echo '<pre>';print_r($data);echo '</pre>';die;
                if (!empty($data))
                    $data[0]['state'] = 1;
                else
                    $data[0]['state'] = 0;
            }
        }
        return $data[0]['state'];
    }

    /**
     * When you create a new permission this function generate the relation between the permission and the all users.
     * @return true
     */

    public function generateAllPermissionForUsers($id_permission){

        $users = User::find()->all();
        foreach ($users as $user) {
            //echo '<pre>';print_r($user);echo '</pre>';die;
            //echo "email {$user->email} <br>";
            $model = new PermissionsUsers();
            $model->id_permission = $id_permission;
            $model->id_user = $user->id;
            $model->state = 0;
            $model->save();
        }
        return true;
    }

    /* This function is executed after the user has permission to the action */
    public function hasPermissionContent($type, $userIdRole, $id)
    {
        if ($userIdRole == Yii::$app->params['CLIENT_ROLE']) {
            $id_project = Client::find()->getOtaProjectsClientByUser(Yii::$app->user->identity->id);

            if ($type == 'otaprojects') {
                //echo $userIdRole. ' -- ' . Yii::$app->user->identity->id . ' id_project ' . $id_project . ' id ' .  $id; //die;
                return OtaProjects::find()->getClientAccessOtaProjects($id_project, $id);
            } else if ($type == 'builds') {

            }
            else
                return false;
        }
        return true;

    }


    public function obtainInheritPermissions($root, $level, $lft, $rgt){

        $multiple = false;
        if ($level == 1) {
            $data = Yii::$app->db->createCommand(' SELECT *
                FROM groups g
                LEFT JOIN permissions_groups pg ON pg.id_group = g.id
                LEFT JOIN permissions p ON p.id = pg.id_permission
                WHERE root = :root AND lvl < :level AND pg.state = 1
                ORDER BY root, lft'
                , array('root' => $root, 'level' => $level) )->queryAll();
        }
        else if ($level >  1){
            $x = $level - 1;
            $data = Yii::$app->db->createCommand('
                SELECT *
                FROM groups g
                LEFT JOIN permissions_groups pg ON pg.id_group = g.id
                LEFT JOIN permissions p ON p.id = pg.id_permission
                WHERE g.lft < :lft  AND g.rgt > :rgt AND g.root = :root AND g.lvl = :level AND pg.state = 1'
                , array('root' => $root, 'level' => $x, 'rgt' => $rgt, 'lft' => $lft) )->queryAll();

            /*$data = Yii::$app->db->createCommand(' SELECT *
                FROM groups g
                LEFT JOIN permissions_groups pg ON pg.id_group = g.id
                LEFT JOIN permissions p ON p.id = pg.id_permission
                WHERE root = :root AND lvl = :level AND pg.state = 1
                ORDER BY root, lft'
                , array('root' => $root, 'level' => $x) )->queryAll();*/

        }
        //echo '<pre>';print_r($data);echo'</pre>'; //die;
        return $data;
    }


    public function createContentPermission($contentType, $name, $type){

        $model = new Permissions();
        $model->name = $contentType.'_'.$name.'_'.$type;
        $model->label =  $contentType.' '.$name.' '.$type;
        $model->permission_type = 1;
        $model->save();
        $id_permission = $model->id;
        $users = User::find()->all();
        foreach ($users as $user) {
            //echo '<pre>';print_r($user);echo '</pre>';die;
            //echo "email {$user->email} <br>";
            $model = new PermissionsUsers();
            $model->id_permission = $id_permission;
            $model->id_user = $user->id;
            $model->state = 0;
            $model->save();
        }
        return true;
    }

    public function getPermissionsUserByGroup ($id_user){

        $user_groups = Yii::$app->db->createCommand('
                        SELECT *
                        FROM user_groups ug
                        LEFT JOIN  groups g ON g.id = ug.id_group
                        WHERE ug.id_user = :id_user'
            , array('id_user' => $id_user) )->queryAll();

        $parent = array();

        foreach ($user_groups as $params) {
            //echo '<pre>';print_r($params['lvl']);echo '</pre>';
            //echo '<pre>';print_r($params['id']);echo '</pre>';
            //echo '<br>====<br>';

            $maxlevel = $params['lvl'];

            if ($params['lvl'] > 0) {
                $level = $params['lvl'];
                do {
                    //echo '<br>*****<br>'.$params['name'].' --- '.$params['root'].' level '.$level.' rgt '.$params['rgt'].' lft '.  $params['lft'].'<br>*****<br>';
                    $temp = Permissions::find()->obtainInheritPermissions($params['root'], $level, $params['rgt'], $params['lft']);
                    if (!empty($temp)) $parent[$params['name']] = $temp;
                    $level = $level - 1;
                } while ($level > 0);
            }

            $selected  = PermissionsGroups::find()->with('idPermissions')->with('idGroup')->where('id_group = :idgroup',  [':idgroup' => $params['id']])->all();
            if (!empty ($selected)) {
                foreach ($selected as $sel) {
                    $parent[$params['name']][] = $sel->attributes;
                }
            }
        }

        $permissions = array();
        foreach ($parent as $k => $par) {
            foreach ($par as $p){
                $permissions[] = $p['id_permission'];
            }
        }
        //echo '<pre>';print_r($permissions); echo '</pre><br>------<br>'; die;
        return array_unique($permissions);
    }
}
