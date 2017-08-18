<?php

namespace backend\controllers;

use Yii;
use backend\models\OtaProjects;
use backend\models\OtaProjectsSearch;
use backend\models\Builds;
use backend\models\BuildsSearch;
use backend\models\OtaBuildTypes;
use backend\models\OtaProjectsBuildtypes;
use backend\models\Permissions;
use common\models\User;
use backend\models\UserOptions;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * OtaprojectsController implements the CRUD actions for OtaProjects model.
 */
class OtaprojectsController extends CController
{
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

    public function beforeAction($action)
    {
        if (isset(Yii::$app->user->identity->id)) {
            if (($this->action->id == 'index') || ($this->action->id == 'create') || ($this->action->id == 'update') || ($this->action->id == 'delete')) {
                $permission = $this->action->controller->id.'_'.$this->action->id;
                $hasPermission = Permissions::find()->hasPermission($permission);
                $userIdRole = User::getUserIdRole();
                //echo $permission;die;
                if (($hasPermission == 0) || ((($permission == 'otaprojects_update') || ($permission == 'otaprojects_delete')) && (($userIdRole == Yii::$app->params['QA_ROLE']) || ($userIdRole == Yii::$app->params['CLIENT_ROLE'])))) {
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
     * Lists all OtaProjects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $option = UserOptions::find()->getVariable(Yii::$app->user->id, 'pages_table_otaprojects');

        if ((isset($_GET['OtaProjectsSearch']['pagesize'])) && ($_GET['OtaProjectsSearch']['pagesize'] != (int)$option['value'])) {
            $useroption = UserOptions::find()->where('id = :id_option', [':id_option' => $option['id']])->one();
            $useroption->value = $_GET['OtaProjectsSearch']['pagesize'];
            $useroption->save();
        }
        $searchModel = new OtaProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (\Yii::$app->devicedetect->isMobile())
            $view = 'indexmobile';
        else
            $view = 'index';

        return $this->render($view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OtaProjects model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $userIdRole = User::getUserIdRole();

        $hasPermissionContent = Permissions::find()->hasPermissionContent('otaprojects', $userIdRole, $id);
        //echo $permission.'<br>'.$hasPermission.'<br>'.$hasPermissionContent;die;
        if ($hasPermissionContent) {

            $option = UserOptions::find()->getVariable(Yii::$app->user->id, 'pages_table_otaviews');

            if ((isset($_GET['BuildsSearch']['pagesize'])) && ($_GET['BuildsSearch']['pagesize'] != (int)$option['value'])) {
                $useroption = UserOptions::find()->where('id = :id_option', [':id_option' => $option['id']])->one();
                $useroption->value = $_GET['BuildsSearch']['pagesize'];
                $useroption->save();
            }
            $params = Yii::$app->request->queryParams;

            $params['BuildsSearch']['buiProIdFK'] = $id;
            $params['BuildsSearch']['buiStatus'] = 0;


            $searchBuilds = new BuildsSearch();
            $dataProvider = $searchBuilds->search($params);

            if (\Yii::$app->devicedetect->isMobile())
                $view = 'viewmobile';
            else
                $view = 'view';

            return $this->render($view, [
                'model' => $this->findModel($id, 0),
                'searchBuilds' => $searchBuilds,
                'dataProvider' => $dataProvider,
            ]);
        }
        else {
            throw new MethodNotAllowedHttpException('This content doesn\'t exist or  You don\'t have permission to see it.');
        }
    }

    /**
     * Creates a new OtaProjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OtaProjects();

        $otaBuildTypes = OtaBuildTypes::find()->all();
        foreach ($otaBuildTypes as $buildtypes) {
            $data[$buildtypes['id']] =  $buildtypes['name'];
        }

        $selected = array();
        $value = array();

        if ($model->load(Yii::$app->request->post())) {

            $model->safename = $this->_GenerateSafeFileName($model->name);
            $model->proHash = $this->_GenerateHash();
            $model->proAPIKey = $this->_GenerateHash();
            $model->proAPIBuildKey = $this->_GenerateSecureApiHash($model->name);

            if ($model->save()) {
                $post = Yii::$app->request->post();
                if (isset($post['proBuildType'])) {
                    foreach ($post['proBuildType'] as $tt){
                        if (empty(intval($tt))) {
                            $exist = OtaBuildTypes::find()->where('name LIKE :name')->addParams([':name'=>$tt])->one();
                            if (isset($exist)) {
                                $tt = $exist->id;
                            } else {
                                $buildtypes = new OtaBuildTypes;
                                $buildtypes->name = $tt;
                                $buildtypes->save();
                                $tt = $buildtypes->id;
                            }
                        }

                        $aux = new OtaProjectsBuildtypes();
                        $aux->id_ota_project = $model->id;
                        $aux->id_ota_buildtypes = $tt;
                        $aux->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'select_buildtype' => $selected,
                    'value' => $value,
                    'ota_buildtypes' => $data,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'select_buildtype' => $selected,
                'value' => $value,
                'ota_buildtypes' => $data,
            ]);
        }
    }

    /**
     * Updates an existing OtaProjects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $otaBuildTypes = OtaBuildTypes::find()->all();
        foreach ($otaBuildTypes as $buildtypes) {
            $data[$buildtypes['id']] =  $buildtypes['name'];
        }

        $selected = OtaProjectsBuildtypes::find()->with('idOtaProject')->with('idOtaBuildtypes')->where('id_ota_project = :idotaproject',  [':idotaproject' => $id])->all();

        if (!empty ($selected)) {
            foreach ($selected as $sel)
                $value[] = (int)$sel->idOtaBuildtypes->id;
        }
        else $value = array();

        if ($model->load(Yii::$app->request->post())) {
            $this->_process($model);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'select_buildtype' => $selected,
                'value' => $value,
                'ota_buildtypes' => $data,
            ]);
        }
    }


    private function _process($model) {

        $post = Yii::$app->request->post();

        if(isset($post['OtaProjects']))
        {
            $model->attributes=$_POST['OtaProjects'];
            $model->updated_at = strtotime('today UTC');
            if($model->save()){
                OtaProjectsBuildtypes::deleteAll(['id_ota_project' => $model->id ]);

                //OtaProjectsBuildTypes
                if (isset($post['proBuildType'])) {
                    foreach ($post['proBuildType'] as $tt){

                        if (empty(intval($tt))) {
                            $exist = OtaBuildTypes::find()->where('name LIKE :name')->addParams([':name'=>$tt])->one();
                            if (isset($exist)) {
                                $tt = $exist->id;
                            } else {
                                $buildtypes = new OtaBuildTypes;
                                $buildtypes->name = $tt;
                                $buildtypes->save();
                                $tt = $buildtypes->id;
                            }
                        }

                        $aux = new OtaProjectsBuildtypes();
                        $aux->id_ota_project = $model->id;
                        $aux->id_ota_buildtypes = $tt;
                        $aux->save();
                    }
                }
                $this->redirect(array('view','id'=>$model->id));
            }
        }
    }

    /**
     * Deletes an existing OtaProjects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $builds = Builds::find()->where('buiProIdFK = :build_id AND buiStatus != 9',  [':build_id' => $id])->all();

        if (empty($builds)){
            $otaProjects = $this->findModel($id);
            $otaProjects->deleted = 1;
            $otaProjects->save();
            $message = 1;
        } else {
            $message = 2;
        }


        $searchModel = new OtaProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'message' => $message,
        ]);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the OtaProjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OtaProjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $deleted = false)
    {
        $condition = [
            'id' => $id
        ];


        if (is_int($deleted)) {
            $condition['deleted'] = $deleted;
        }

        if (($model = OtaProjects::findOne($condition)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected static function _GenerateHash($length=12) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0,strlen($characters) - 1)];
        }

        return $string;
    }

    public static function _GenerateSecureApiHash($projectName, $length=30)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0,strlen($characters) - 1)];
        }

        $hash = "";

        $hash = crypt($projectName . $string, $string.$string);

        for ($p = $length; $p > 0 ; $p--) {
            $string .= $characters[mt_rand(0,strlen($characters) - 1)];
        }

        $hash .= $string;

        $chars = array("/", ".");
        $hash = str_replace($chars, "_", $hash);

        return substr($hash, 0, $length);
    }

    public static function _GenerateSafeFileName($text) {
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('/[^-\w]+/', '', $text);
        return $text;
    }

    public function actionClone() {
        $result = array(
            'error' => 0,
            'message' => 'Build cloned properly'
        );
        $permission = $this->action->controller->id.'_create';
        $hasPermission = Permissions::find()->hasPermission($permission);
        //echo $permission.'<br>';die;
        if ($hasPermission == 0) {
            $result['error'] = 1;
            $result['message'] = 'You don\'t have the necesary permissions';
        } else  if (isset($_POST['id'])) {
            $id = $_POST['id'];
            if ($_POST['buiHash']) {
                $buiHash = $_POST['buiHash'];
                $buildToCopy = Builds::find()->where('buiHash = :buiHash AND buiStatus != 9',  [':buiHash' => $buiHash])->one();

                $newBuild = new Builds();
                $newBuild->attributes = $buildToCopy->attributes;
                $newBuild->buiId = null;
                $newBuild->buiChangeLog = '';
                $newBuild->buiProIdFK = $id;
                $newBuild->created_at = strtotime('today UTC');
                $newBuild->updated_at = strtotime('today UTC');

                $timestamp = strtotime(date('Y-m-d H:i:s'));
                $newBuild->buiSafename = Builds::_GenerateSafeFileName((string) $newBuild->buiName);
                $newBuild->buiHash = Builds::_GenerateHash();
                $safe = Builds::_GenerateSafeFileName((string) $id.'_'.$timestamp);

                if ($newBuild->save()) {
                    $path_file = Yii::$app->params["BUILD_DIR"] . $newBuild->buiFile;
                    $new_filename = str_replace($buildToCopy->buiId, $newBuild->buiId, $newBuild->buiFile);
                    $path_file_new = Yii::$app->params["BUILD_DIR"] . $new_filename;
                    if (file_exists($path_file) && copy($path_file, $path_file_new)) {
                        $newBuild->buiFile = $new_filename;
                        $newBuild->save();
                    } else {
                        $result['error'] = 1;
                        $result['message'] = 'Build cloned but an error occurred copying the file';
                    }
                } else {
                    $result['error'] = 1;
                    $result['message'] = 'The new build can\'t be copied';
                }


            } else {
                $result['error'] = 1;
                $result['message'] = 'The request doesn\'t have the correct format';
            }
            $this->redirect("/otaprojects/{$id}");
            Yii::$app->getSession()->setFlash('cloneResult', $result);
        } else {
            echo "ko";
        }
    }

}
