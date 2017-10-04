<?php

namespace backend\controllers;


use Yii;
use backend\models\Project;
use backend\models\ProjectSearch;
use backend\models\Permissions;
use backend\models\Client;
use backend\models\Type;
use backend\models\ProjectType;
use backend\models\OtaProjects;
use backend\models\ProjectOtaProjects;
use backend\models\NewField;
use backend\models\NewFieldProject;
use backend\models\NewFieldValues;
use backend\models\Utils;
use common\models\User;
use yii\web\Controller;
//use backend\models\Model;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use backend\base\Model;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (isset(Yii::$app->user->identity->id)) {
            if (($this->action->id == 'index') || ($this->action->id == 'create') || ($this->action->id == 'update') || ($this->action->id == 'delete')) {
                $permission = $this->action->controller->id.'_'.$this->action->id;
                //$hasPermission = Permissions::find()->hasPermission($permission);
                $userIdRole = User::getUserIdRole();
                //if (($hasPermission == 0) || ... ) { //hasPermission is not working!
                if ((($permission == 'project_index') || ($permission == 'project_create') || ($permission == 'project_delete')) && (($userIdRole == Yii::$app->params['QA_ROLE']) || ($userIdRole == Yii::$app->params['CLIENT_ROLE']))) {
                    throw new MethodNotAllowedHttpException('You don\'t have permission to see this content.');
                }
                if (!isset($_SESSION['skin-color'])) {
                    $_SESSION['skin-color'] = 'skin-blue';
                }
            }
            return true;
        }
        else {
            $this->redirect('/site/logout');
        }

    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        if (Yii::$app->user->identity->id == 1)
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        else
            $dataProvider = $searchModel->searchPermission(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        //$ids_new_field = NewFieldProject::find()->where('project_id = :projectid',  [':projectid' => $id])->all();

        $new_field = '';
        /*
        foreach ($ids_new_field as $t) {
            $new_field[]  = NewField::find()->with('projects')->where('id = :fieldid',  [':fieldid' => $t->new_field_id])->all();
            //echo '<pre>';print_r($new_field);echo '</pre>'; die;
        }
        */

        return $this->render('view', [
            'model' => $this->findModel($id),
            'new_field'=>$new_field,
            //'client'=>$client,
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $modelsClient = [new Client];

        $types = Type::find()->all();

        if ($model->load(Yii::$app->request->post())) {

            //Create permissions
            $id_permission = Permissions:: find()->createContentPermission('project', Utils::cleanString($model->name), 'view');
            $model->permission_view = $id_permission;
            $id_permission = Permissions:: find()->createContentPermission('project', Utils::cleanString($model->name), 'update');
            $model->permission_update = $id_permission;

            //$this->_process($model, $modelsClient);

            /*

            $id_permission = Permissions:: find()->createContentPermission('project', $model->name, 'view');
            $model->permission_view = $id_permission;
            $id_permission = Permissions:: find()->createContentPermission('project', $model->name, 'update');
            $model->permission_update = $id_permission;
            */

            if ($model->save()) {
                $modelsClient = Model::createMultiple(Client::classname());
                Model::loadMultiple($modelsClient, Yii::$app->request->post());

                // ajax validation
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ArrayHelper::merge(
                        ActiveForm::validateMultiple($modelsClient),
                        ActiveForm::validate($model)
                    );
                }

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsClient) && $valid;

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsClient as $modelClient) {
                                $modelClient->client_id = $model->id;
                                if (!($flag = $modelClient->save(false))) {
                                    //print_r($modelClient->getErrors()); die;
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'modelsClient' => (empty($modelsClient)) ? [new Client] : $modelsClient,
                    'types' => $types
                ]);
            }

        } //else {
            return $this->render('create', [
                'model' => $model,
                'modelsClient' => (empty($modelsClient)) ? [new Client] : $modelsClient,
                'types' => $types
            ]);
        //}
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $types = Type::find()->all();
        $modelsClient = $model->client;

        //OTA Projects
        $otaProjects = OtaProjects::find()->all();

        if (!empty($otaProjects)) {
            foreach ($otaProjects as $otaproject) {
                $ota_projects[$otaproject->id] =  $otaproject->name;
            }
        }
        else $ota_projects = array();

        //OTA Projects
        $clients = OtaProjects::find()->all();

        if (!empty($otaProjects)) {
            foreach ($otaProjects as $otaproject) {
                $ota_projects[$otaproject->id] =  $otaproject->name;
            }
        }
        else $ota_projects = array();

        $pt_array = ProjectOtaProjects::find()->where('id_project = :idproject',  [':idproject' => $id])->all();
        $value_otaprojects = array();
        foreach ($pt_array as $tt) {
            $value_otaprojects[] = $tt->attributes['id_ota_project'];
        }

        //echo '<pre>'; print_r($value_otaprojects); echo '</pre>'; die;
        /*
        $pt_array = OtaProjects::find()->where('id_project = :idproject',  [':idproject' => $id])->all();
        $values = array();
        foreach ($pt_array as $tt) {
            $values[] = $tt->attributes['type_id'];
        }
        */

        //Project Types
        $pt_array = ProjectType::find()->where('project_id = :idproject',  [':idproject' => $id])->all();
        $project_types = array();
        foreach ($pt_array as $tt) {
            $project_types[] = $tt->attributes['type_id'];
        }

        //echo '<pre>';print_r($project_types);echo '</pre>';
        //echo '<pre>';print_r($model);echo '</pre>';
        //echo '<pre>';print_r($_POST);echo '</pre>';
        //echo '<pre>';print_r($modelsClient);echo '</pre>';
        //echo '<pre>';print_r(Yii::$app->request->post()['Client']);echo '</pre>';
        //die;

        if ($model->load(Yii::$app->request->post())) {
            //echo '<pre>';print_r($model);echo '</pre>'; //die;
            //$modelsClient = Yii::$app->request->post()['Client'];
            $oldIDs = ArrayHelper::map($modelsClient, 'id', 'id');
            $modelsClient = Model::createMultiple(Client::classname(), $modelsClient);
            Model::loadMultiple($modelsClient, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsClient, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsClient),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            //$valid2 = $valid;
            //$valid = Model::validateMultiple($modelsClient) && $valid;
            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        //CLIENTS
                        if (!empty($deletedIDs)) {
                            Client::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsClient as $modelsClient) {
                            $modelsClient->id_project = $model->id;
                            if (!($flag = $modelsClient->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                            $user = User::adminUser($modelsClient, Yii::$app->params['CLIENT_ROLE']);
                            //var_dump($user);
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        $valid2 = true;

                        $model->image_logo = UploadedFile::getInstance($model, 'image_logo');

                        if (!empty($model->image_logo)) {
                            // file is uploaded successfully
                            $url_logo = $model->upload();
                            $model->logo = $url_logo;


                            //echo 'eee';;
                            //echo '<br>MODEL1: <pre>'; print_r($model->attributes); echo '</pre>'; //die;
                            //var_dump($model->validate());
                            //var_dump($model->getErrors());

                            $model->save();

                            //echo '<br>MODEL2: <pre>'; print_r($model->attributes); echo '</pre>'; die;

                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }


            if ($valid2) {
                //ProjectOtaProjects
                ProjectOtaProjects::deleteAll(['id_project' => $model->id]);

                if (isset(Yii::$app->request->post()['otaProjects'])) {
                    $otas = Yii::$app->request->post()['otaProjects'];
                    //echo '<pre>';print_r($otas);echo '</pre>';die;
                    foreach ($otas as $tt) {
                        $projectOta = new ProjectOtaProjects();
                        $projectOta->id_project = $model->id;
                        $projectOta->id_ota_project = $tt;
                        $projectOta->save();
                    }
                }

                //ProjectType
                ProjectType::deleteAll(['project_id' => $model->id]);

                if (isset(Yii::$app->request->post()['Project']['projectType'])) {
                    $types = Yii::$app->request->post()['Project']['projectType'];
                    //echo '<pre>';print_r($types);echo '</pre>';die;
                    foreach ($types as $tt) {
                        $projectType = new ProjectType();
                        $projectType->project_id = $model->id;
                        $projectType->type_id = $tt;
                        $projectType->save();
                    }
                }
                //Start Dynamic Fields
                /*
                if (isset(Yii::$app->request->post()['NewField'])) {
                    $fields = Yii::$app->request->post()['NewField'];

                    foreach ($fields as $k=>$ff){
                        //echo '<pre>';print_r($k);echo '</pre>';die;

                        //$attribs = array('view_id'=>$model->id, 'new_field'=>$k);
                        //$criteria = new CDbCriteria(array('order'=>'id DESC'));
                        //$field = NewFieldValues::model()->findAllByAttributes($attribs, $criteria);
                        $field = NewFieldValues::find()
                            ->where('new_field = :idnewfield AND view_id = :viewid',  [':idnewfield' => $k, 'viewid' => $model->id ])
                            ->orderBy('id DESC')
                            ->all();

                        if (!empty($field)) {
                            $newField = $field[0];
                            $newField->value = $ff;
                        }
                        else {
                            $newField = new NewFieldValues();
                            $newField->new_field = (int) $k;
                            $newField->view_id = $model->id;
                            $newField->value = $ff;
                        }
                        $newField->save();
                    }
                }
                */
                // End Save NewFieldValues

                return $this->redirect(['view', 'id' => $model->id]);
            }



            //$this->_process($model, $modelsClient);
        }

        $new_field = '';
        /*
        //$ids_new_field = NewFieldProject::find()->where('project_id = :idproject',  [':idproject' => $model->id])->all();
        foreach ($ids_new_field as $t) {
            $new_field[] = NewField::find()->with('projects')->where('id = :idnewfield',  [':idnewfield' => $t->new_field_id])->all();
        }
        */
        return $this->render('update', [
            'model' => $model,
            'new_field'=>$new_field,
            'modelsClient' => (empty($modelsClient)) ? [new Client] : $modelsClient,
            'types' => $types,
            'project_types' => $project_types,
            'ota_projects' => $ota_projects,
            //'value_otaprojects' => $values,
            'value_otaprojects' => $value_otaprojects,
        ]);

    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    private function _process($model, $modelsClient){

        $oldIDs = ArrayHelper::map($modelsClient, 'id', 'id');
        $modelsClient = Model::createMultiple(Client::classname(), $modelsClient);
        //echo '<pre>'; print_r($oldIDs); echo '</pre>';
        //echo '<pre>'; print_r($modelsClient); echo '</pre>'; die;

        Model::loadMultiple($modelsClient, Yii::$app->request->post());
        //echo '<pre>'; print_r($modelsClient); echo '</pre>'; die;

        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsClient, 'id', 'id')));


        // ajax validation
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ArrayHelper::merge(
                ActiveForm::validateMultiple($modelsClient),
                ActiveForm::validate($model)
            );
        }

        $valid = $model->validate();
        print_r($model->getErrors());

        $valid = Model::validateMultiple($modelsClient) && $valid;

        //Upload LOGO
        $model->image_logo = UploadedFile::getInstance($model, 'image_logo');

        //if ((!empty($model->image_logo)) && ($model->logo = $model->upload())) {
        if (!empty($model->image_logo)) {
            // file is uploaded successfully
            $model->logo = $model->upload();
        }
        var_dump($valid);
//echo 'aki'; die;
        if (1) {
            echo '<pre>';print_r($model);echo '</pre>';die;
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {
                    //echo 'DELETE IDS<pre>';print_r($deletedIDs);echo '</pre>';die;
                    if (! empty($deletedIDs)) {
                        Client::deleteAll(['id' => $deletedIDs]);
                    }
                    //echo '<pre>'; print_r($modelsClient); echo '</pre>'; die;
                    foreach ($modelsClient as $modelClient) {
                        $modelClient->id_project = $model->id;
                        if (!($flag = $modelClient->save(false))) {
                            print_r($modelClient->getErrors()); die;
                            $transaction->rollBack();
                            break;
                        }
                    }

                    //ProjectType
                    ProjectType::deleteAll(['project_id' => $model->id ]);
                    if (isset(Yii::$app->request->post()['Project']['projectType'])) {
                        $types = Yii::$app->request->post()['Project']['projectType'] ;
                        //echo '<pre>';print_r($types);echo '</pre>';die;
                        foreach ($types as $tt){
                            $projectType = new ProjectType();
                            $projectType->project_id = $model->id;
                            $projectType->type_id = $tt;
                            $projectType->save();
                        }
                    }

                    //Start Dynamic Fields
                    /*
                    if (isset(Yii::$app->request->post()['NewField'])) {
                        $fields = Yii::$app->request->post()['NewField'];

                        foreach ($fields as $k=>$ff){
                            //echo '<pre>';print_r($k);echo '</pre>';die;

                            //$attribs = array('view_id'=>$model->id, 'new_field'=>$k);
                            //$criteria = new CDbCriteria(array('order'=>'id DESC'));
                            //$field = NewFieldValues::model()->findAllByAttributes($attribs, $criteria);
                            $field = NewFieldValues::find()
                                ->where('new_field = :idnewfield AND view_id = :viewid',  [':idnewfield' => $k, 'viewid' => $model->id ])
                                ->orderBy('id DESC')
                                ->all();

                            if (!empty($field)) {
                                $newField = $field[0];
                                $newField->value = $ff;
                            }
                            else {
                                $newField = new NewFieldValues();
                                $newField->new_field = (int) $k;
                                $newField->view_id = $model->id;
                                $newField->value = $ff;
                            }
                            $newField->save();
                        }
                    }
                    */
                    // End Save NewFieldValues
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

    }
/*
    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->logo = UploadedFile::getInstance($model, 'logo');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }
*/

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
