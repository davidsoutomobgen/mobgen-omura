<?php

namespace backend\controllers;

use Yii;
use backend\models\Groups;
use backend\models\GroupsSearch;
use backend\models\UserGroups;
use backend\models\PermissionsGroups;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends  Controller
{
    public $title;
    //public $setupPermission;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->title = 'Groups';

        if (!empty(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            PermissionsGroups::deleteAll(['id_group' => $post['id_group']]);

            //var_dump(Yii::$app->request->post());die;
            if (!empty($post['permissions'])) {
                foreach ($post['permissions'] as $perm) {

                    //var_dump(Yii::$app->request->post());die;
                    //echo '<br>*****<br>';
                    /*
                    var_dump($deletedIDs);
                    die;
                    if (! empty($deletedIDs)) {
                        PermissionsGroups::deleteAll(['id' => $deletedIDs]);
                    }
                    */
                    $model = new PermissionsGroups();
                    $model->id_group = $_POST['id_group'];
                    $model->id_permission = $perm;
                    $model->state = 1;
                    $model->save();
                    //echo '<br>*****<br>';
                }
            }

            /*
            return $this->render('/groups/index', [
                'time' => 'Saved correctly',
            ]);
            */
        }

        return $this->render('index');
    }

    public function actionUsers()
    {
        $this->title = 'Setup User on Groups';
        if (!empty(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();


            UserGroups::deleteAll(['id_group' => $post['id_group']]);
            if (!empty($post['users'])) {
                foreach ($post['users'] as $perm) {

                    //var_dump(Yii::$app->request->post());die;
                    //echo '<br>*****<br>';
                    /*
                    var_dump($deletedIDs);
                    die;
                    if (! empty($deletedIDs)) {
                        PermissionsGroups::deleteAll(['id' => $deletedIDs]);
                    }
                    */
                    $model = new UserGroups();
                    $model->id_group = $_POST['id_group'];
                    $model->id_user = $perm;
                    $model->state = 1;
                    $model->save();
                }
            }
        }

        return $this->render('users');
    }

    /*
    public function actionSaved()
    {
        if (!empty(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();

            PermissionsGroups::deleteAll(['id_group' => $post['id_group'] ]);

            //var_dump(Yii::$app->request->post());die;
            if (!empty($post['permissions'])) {
                foreach ($post['permissions'] as $perm) {

                    //var_dump(Yii::$app->request->post());die;
                    //echo '<br>*****<br>';

                    $model = new PermissionsGroups();
                    $model->id_group = $_POST['id_group'];
                    $model->id_permission = $perm;
                    $model->state = 1;
                    $model->save();
                    //echo '<br>*****<br>';
                }
            }
            //var_dump($_POST);
            //die;

            //$model->
            //if ($model->load(Yii::$app->request->post()) && $model->save())
            //$model = $this->findModel($id);
            //$this->findModel($id)->delete();

            return $this->render('/groups/index', [
                    'time' => 'Saved correctly',
                ]);
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');





    }
    */
}
