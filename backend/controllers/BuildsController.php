<?php

namespace backend\controllers;

use Yii;
use backend\models\Builds;
use backend\models\BuildsSearch;
use backend\models\BuildsNotification;
use backend\models\BuildsNotificationSearch;
use backend\models\OtaProjects;
use backend\models\OtaBuildTypes;
use backend\models\OtaProjectsBuildtypes;
use backend\models\Templates;
use backend\models\Utils;
use backend\models\Permissions;
use common\models\User;


use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;


/**
 * BuildsController implements the CRUD actions for Builds model.
 */
class BuildsController extends Controller
{
    public $send_email;

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
                if (($hasPermission == 0) || (($permission == 'builds_update') && ($userIdRole == 11))) {
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
     * Lists all Builds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BuildsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Builds model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Builds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = 0)
    {
        $model = new Builds();
        $model->buiProIdFK = $id;

        //Build Types
        $otaBuildTypes = OtaProjectsBuildtypes::find()->with('idOtaBuildtypes')->where('id_ota_project = :id_ota_project',  [':id_ota_project' => $id])->all();
        if (!empty($otaBuildTypes)) {
	    foreach ($otaBuildTypes as $buildtypes) {
                $data[$buildtypes->id] =  $buildtypes->idOtaBuildtypes->name;
            }
	}
	else $data = array();
        $selectedBuildTypes = array();

        //Mails notifications
        $otaProject = OtaProjects::find()->where('id = :id_ota_project',  [':id_ota_project' => $id])->one();
        $modelNotification = new BuildsNotification();
        $modelNotification->email = $otaProject->default_notify_email;

        //Template
        $templates = Templates::getTemplatesTemporaly();

        if ($model->load(Yii::$app->request->post())) {
            //$process = $this->_process($id, $model);
            //echo '<pre>';print_r(Yii::$app->request->post()); echo '</pre>'; die;

            $timestamp = $model->buiSafename;
            $extension = strtolower(Builds::_GetExtension($model->buiFile));
            //$model->buiSafename = Builds::_RemoveExtension($model->buiFile);
            $model->buiSafename = Builds::_GenerateSafeFileName((string) $model->buiName);
            $model->buiHash = Builds::_GenerateHash();
            $safe = Builds::_GenerateSafeFileName((string) $id.'_'.$timestamp);

            $model->created_by = Yii::$app->user->identity->id;

            if ($extension == "ipa") {
                $model->buiType = 0; // iOS
                $model->buiCerIdFK = 1;
            } elseif ($extension == "apk") {
                $model->buiType = 1; // Android
                $model->buiCerIdFK = 0;
            }

            //$temp_file =  Yii::getAlias('@webroot') . Yii::$app->params['TEMP_BUILD_DIR'] . $safe .".". $extension;
            $temp_file =  Yii::$app->params['TEMP_BUILD_DIR'] . $safe .".". $extension;

            if (file_exists($temp_file)) {
                if ($model->save()) {
                    $filename = $id.'-'.$timestamp.'.'.$extension;
                    //$path_file = Yii::getAlias('@webroot') . Yii::$app->params["TEMP_BUILD_DIR"] . $filename;
                    $path_file = Yii::$app->params["TEMP_BUILD_DIR"] . $filename;

                    $new_filename = $model->buiId . "." . $extension;
                    $model->buiFile = $new_filename;
                    $model->save();

                    //$new_path_file = Yii::getAlias('@webroot') . Yii::$app->params["BUILD_DIR"] . $new_filename;
                    $new_path_file = Yii::$app->params["BUILD_DIR"] . $new_filename;
                    rename($path_file, $new_path_file);

                    if ($model->buiSendEmail == 1)
                        $this->_sendEmail($model, Yii::$app->request->post()['BuildsNotification']['email']);

                    $otaProject->updated_at = strtotime('today UTC');
                    $otaProject->save();
                    //$this->redirect(['view', 'id' => $model->buiId]);
                    $this->redirect(['/otaprojects/'.$model->buiProIdFK]);
                } else {
                    print_r($model->getErrors());
                    echo 'error haciendo save ';die;
                    return false;
                    $this->render('create', [
                        'model' => $model,
                        'ota_buildtypes' => $data,
                        'selected_buildtypes' => $selectedBuildTypes,
                        'templates' => $templates,
                        'modelNotification' => $modelNotification,
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'ota_buildtypes' => $data,
                    'selected_buildtypes' => $selectedBuildTypes,
                    'templates' => $templates,
                    'modelNotification' => $modelNotification,
                ]);
            }
        } else {
            $model->buiTemplate = 0;
            $model->buiSendEmail = 1;

            return $this->render('create', [
                'model' => $model,
                'ota_buildtypes' => $data,
                'selected_buildtypes' => $selectedBuildTypes,
                'templates' => $templates,
                'modelNotification' => $modelNotification,
            ]);
        }
    }

    /**
     * Updates an existing Builds model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //BuildsNotifications
        $params = Yii::$app->request->queryParams;
        $params['BuildsNotificationSearch']['buiId'] =  $id;
        $searchBuildsNotification = new BuildsNotificationSearch();
        $dataProvider = $searchBuildsNotification->search($params);

        $model = $this->findModel($id);

        if ($model->buiStatus == 9) {
            throw new MethodNotAllowedHttpException('This content doesn\'t exist.');
        }
        else {

            //Build Types
            $otaBuildTypes = OtaProjectsBuildtypes::find()->with('idOtaBuildtypes')
                ->where('id_ota_project = :id_ota_project', [':id_ota_project' => $model->buiProIdFK])
                ->all();
            $data = array();
            foreach ($otaBuildTypes as $buildtypes) {
                $data[$buildtypes->id] = $buildtypes->idOtaBuildtypes->name;
            }
            $selectedBuildTypes = $model->buiBuildType;

            //Mails notifications
            $otaProject = OtaProjects::find()->where('id = :id_ota_project', [':id_ota_project' => $model->buiProIdFK])->one();
            $modelNotification = new BuildsNotification();
            $modelNotification->email = $otaProject->default_notify_email;

            //Template
            $templates = Templates::getTemplatesTemporaly();

            if (!empty(Yii::$app->request->post())) {
                $process = $this->_process($id, $model);

                if ($process) {
                    //return $this->redirect(['view', 'id' => $model->buiId]);
                    $otaProject->updated_at = strtotime('today UTC');
                    $otaProject->save();
                    return $this->redirect(['/otaprojects/' . $model->buiProIdFK]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'ota_buildtypes' => $data,
                        'selected_buildtypes' => $selectedBuildTypes,
                        'templates' => $templates,
                        'modelNotification' => $modelNotification,
                        'searchBuildsNotification' => $searchBuildsNotification,
                        'dataProvider' => $dataProvider,
                        'otaProject' => $otaProject,
                    ]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'ota_buildtypes' => $data,
                    'selected_buildtypes' => $selectedBuildTypes,
                    'templates' => $templates,
                    'modelNotification' => $modelNotification,
                    'searchBuildsNotification' => $searchBuildsNotification,
                    'dataProvider' => $dataProvider,
                    'otaProject' => $otaProject,
                ]);
            }
        }
    }


    private function _process($id, $model){

        $post = Yii::$app->request->post('Builds');

        if (($model->buiFile != $post['buiFile']) && ($post['buiFile'] != '')) {
            if ($post['time'] != $post['buiSafename']) {
                //Update app
                $timestamp = $post['time'];
                $extension = strtolower(Builds::_GetExtension($model->buiFile));
                $safe = Builds::_GenerateSafeFileName((string)$model->buiId . '_' . $model->buiProIdFK . '_' . $timestamp);
            } else {
                //New app
                $timestamp = $model->buiSafename;
                $model->buiSafename = Builds::_RemoveExtension($model->buiFile);
                $model->buiHash = Builds::_GenerateHash();
                $safe = Builds::_GenerateSafeFileName((string)$id . '_' . $model->buiProIdFK . '_' . $timestamp);
                $model->created_by = Yii::$app->user->identity->id;
            }

            //$temp_file = Yii::getAlias('@webroot') . Yii::$app->params['TEMP_BUILD_DIR'] . $safe . "." . $extension;
            $temp_file = Yii::$app->params['TEMP_BUILD_DIR'] . $safe . "." . $extension;

            if ($extension == "ipa") {
                $model->buiType = 0; // iOS
                $model->buiCerIdFK = 1;
            } elseif ($extension == "apk") {
                $model->buiType = 1; // Android
                $model->buiCerIdFK = 0;
            }

            if (file_exists($temp_file)) {
                $model->load(Yii::$app->request->post());

                if ($model->save()) {
                    $new_filename = $model->buiId . "." . $extension;
                    $model->buiFile = $new_filename;
                    $model->save();
                    $new_path_file = Yii::$app->params["BUILD_DIR"] . $new_filename;
                    rename($temp_file, $new_path_file);
                    return true;
                } else {
                    print_r($model->getErrors());
                    echo 'Error doing SAVE';
                    die;
                    return false;
                    /*$this->render('create', [
                    'model' => $model,
                    'ota_buildtypes' => $data,
                    'selected_buildtypes' => $selectedBuildTypes,
                    'templates' => $templates,
                ]);
                    */
                }
            }
            else {
                $buiFileOriginal = $model->buiFile;
                $model->attributes = $post;
                $model->buiFile = $buiFileOriginal;
                if ($model->save()){
                    return true;
                }
                else {
                    print_r($model->getErrors());
                    die;
                    return false;
                }
            }
        }
        else {
            $temp = $model->buiFile;
            $model->load(Yii::$app->request->post());
            $model->buiFile = $temp;
            if ($model->save()) {
                return true;
            } else {
                print_r($model->getErrors());
                echo 'error haciendo save ';die;
                return false;
                /*
                return $this->render('create', [
                    'model' => $model,
                    'ota_buildtypes' => $data,
                    'selected_buildtypes' => $selectedBuildTypes,
                    'templates' => $templates,
                ]);
                */
            }
        }
    }
    /**
     * Deletes an existing Builds model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
        //echo $path_file;die;
        //$this->findModel($id)->delete();
        @unlink($path_file);
        $model->buiStatus = '9';
        $model->save(false);
        return $this->redirect(['/otaprojects/'.$model->buiProIdFK]);

    }

    /**
     * Finds the Builds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Builds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Builds::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBulk(){

        $buiProIdFK = Yii::$app->request->post('buildId');
        $submit = Yii::$app->request->post('submit');
        if ($submit == 1)
            $action=Yii::$app->request->post('action1');
        else
            $action=Yii::$app->request->post('action2');

        $selection=(array)Yii::$app->request->post('selection');
        if (!empty($selection)) {
            foreach ($selection as $id) {

                $model = $this->findModel($id);

                if ($action == 1) {
                    $model->buiFav = 1;
                    $model->save();
                    //print_r($model->getErrors());
                }
                else if ($action == 2) {
                    $model->buiFav = 0;
                    $model->save();
                }
                else if ($action == 3) {
                    $model = $this->findModel($id);
                    $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
                    @unlink($path_file);
                    $model->buiStatus = '9';
                    $model->save(false);
                }
            }
        }

        return $this->redirect(['/otaprojects/' . $buiProIdFK]);
    }

    public function actionLike($id)
    {

        $model = $this->findModel($id);
        if ($model->buiFav == 1) {
            $model->buiFav = 0;
            $icon = '<i class="fa fa-star-o fa-x '.$_SESSION['skin-color'].'"></i>';
        } else {
            $model->buiFav = 1;
            $icon = '<i class="fa fa-star fa-x '.$_SESSION['skin-color'].'"></i>';
        }
        $model->save(false);

        return $icon;
    }

    public function actionDislike($id){

        $model = $this->findModel($id);
        $model->buiFav = 0;
        $model->save(false);
        return true;
    }

    public function actionShow($id){

        $model = $this->findModel($id);

        $model->buiVisibleClient = 1;
        $model->save();

        return $this->redirect(['/otaprojects/'.$model->buiProIdFK]);
    }

    public function actionHidden($id){

        $model = $this->findModel($id);

        $model->buiVisibleClient = 0;
        $model->save();

        return $this->redirect(['/otaprojects/'.$model->buiProIdFK]);
    }

    public function actionFileupload($id = 0){

        $buiId = Yii::$app->request->post('buiId');
        $otaProjectId = Yii::$app->request->post('otaProjectId');
        $timestamp = Yii::$app->request->post('timestamp');
        //echo '<pre>'; print_r($_POST);echo '</pre>'; die;

        //if ($buiId == "null") {
        if (1) {
            //echo '<pre>'; print_r($_FILES);echo '</pre>';//die;
            if (!empty($_FILES)) {
                if ($_FILES['buiFile']['error'][0] === UPLOAD_ERR_OK) {

                    // Device OS
                    $extension = strtolower(Builds::_GetExtension($_FILES['buiFile']['name'][0]));

                    if ($extension == "ipa") {
                        $device_os = 0; // iOS
                    } elseif ($extension == "apk") {
                        $device_os = 1; // Android
                    }

                    if (isset($device_os)) {
                        //Temporal name
                        if ($buiId > 0) {
                            $safe = Builds::_GenerateSafeFileName((string)$buiId . '_' . $otaProjectId . '_' . $timestamp);
                            //$filename = Yii::getAlias('@webroot') . Yii::$app->params["TEMP_BUILD_DIR"] . $safe . "." . $extension;
                            $filename = Yii::$app->params["TEMP_BUILD_DIR"] . $safe . "." . $extension;
                        }
                        else {
                            $safe = Builds::_GenerateSafeFileName((string)$otaProjectId . '_' . $timestamp);
                            //$filename = Yii::getAlias('@webroot') . Yii::$app->params["TEMP_BUILD_DIR"] . $safe . "." . $extension;
                            $filename = Yii::$app->params["TEMP_BUILD_DIR"] . $safe . "." . $extension;
                        }
                        //echo $filename; die;

                        if (isset($otaProjectId) && move_uploaded_file($_FILES['buiFile']['tmp_name'][0], $filename)) {
                            $salida = array('1' => 'Done');
                            return json_encode($salida);
                        }
                        //echo '<pre>'; print_r($safe);echo '</pre>';//die;
                    } else {
                        $error = "File extension not recognized as valid extension.";
                    }
                } else {
                    $error = "Error with uploading file.";// . $_FILES['buiFile']['error'];
                }
            }
            else {
                $salida = array('1' => 'Nothing to do.');
                return json_encode($salida);
            }
        }
        else {
            echo 'This is update ';
        }
    }

    public function actionNotification($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();

        $this->_sendEmail($model, $post['email']);

        //BuildsNotifications
        $params = Yii::$app->request->queryParams;
        $params['BuildsNotificationSearch']['buiId'] =  $id;
        $searchBuildsNotification = new BuildsNotificationSearch();
        $dataProvider = $searchBuildsNotification->search($params);

        return $this->renderPartial('notifications', [
            'searchBuildsNotification' => $searchBuildsNotification,
            'dataProvider' => $dataProvider,
        ]);
    }

    private function _sendEmail($model, $post_emails)
    {
        $emails = explode(',', $post_emails);

        $sendTo = '';
        foreach ($emails as $email){
            $sendTo [] = trim($email);
        }
        $project = OtaProjects::findOne($model->buiProIdFK);

        if ($model->buiDeviceOS == 1) {
            $subject = '[MOBGEN] (OTAShare) '. $project->name;
            if (!empty($model->buiBuildType))
                $subject .=   " - " . $model->buiBuildType;
            if (!empty($model->buiVersion))
                $subject .=   " - " . $model->buiVersion;

            $appType = 'iOS';
        } else {
            $subject = '[MOBGEN] (OTAShare) '. $project->name;
            if (!empty($model->buiBuildType))
                $subject .=   " - " . $model->buiBuildType;
            if (!empty($model->buiVersion))
                $subject .=   " - " . $model->buiVersion;

            $appType = 'Android';
        }

        $sendEmail = Yii::$app->mailer->compose('newBuildAvailableExtraInformationJira', [
            'model' => $model,
            'project' => $project,
            'appType' => $appType])
            ->setFrom(['otashare@mobgen.com' =>'[MOBGEN] (OTAShare)'])
            ->setTo($sendTo)
            ->setSubject($subject)
            ->send();



        $modelNotification = new BuildsNotification();
        $modelNotification->buiId = $model->buiId;
        $modelNotification->email = $post_emails;
        $modelNotification->created_by =  Yii::$app->user->identity->id;
        $modelNotification->save();

        return true;
    }

    /*
    function actionSearch() {

    }
    */

    /*
     * Function to pass the old build types to the new database format
     * */
    function actionSeparatetags($id){
        $ota = OtaProjects::find()->all();
        //echo '<pre>'; print_r($ota);echo '</pre>';die;
        $old = 0;
        $news = 0;
        foreach ($ota as $ot) {
            //$builds = Builds::find()->where('buiProIdFK = :buiProIdFK',  [':buiProIdFK' => $ot->id])->all();
            //echo '<pre>'; print_r($ot);echo '</pre>';die;
            echo '/******<br>* ID: '.$ot->id.' - Name: '. $ot->name.'<br>********/<br />';
            if (!empty($ot->proBuildTypes)) {
                $types = explode(',', $ot->proBuildTypes);
                foreach ($types as $type) {
                    $typ = trim($type);
                    $exist = OtaBuildTypes::find()->where("name LIKE '$typ'")->one();

                    if (!$exist) {
                        echo 'nuevo ' . trim($type) . '<br>'; //die;
                        $exist = new OtaBuildTypes();
                        $exist->name = trim($type);
                        $exist->save();
                        $saved = false;
                    } else {
                        $saved = OtaProjectsBuildtypes::find()->where('id_ota_project = ' . $ot->id . ' AND id_ota_buildtypes = ' . $exist->id)->one();
                    }

                    if (!$saved) {
                        $rel = new OtaProjectsBuildtypes();
                        $rel->id_ota_project = $ot->id;
                        $rel->id_ota_buildtypes = $exist->id;
                        $rel->save();
                        $news++;
                    } else {
                        $old++;
                    }

                }
            }
        }
        //echo '<br />******************<br />Old: ' . $old . '  - News: ' . $news . '<br />******************<br />';
    }

    public function actionDownload($id) {

        $model = Builds::find()->where(" buiId = $id ")->one();

        if (!empty($model)) {
            $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
            $extension = Builds::_GetExtension($model->buiFile);
            $filename = $model->buiSafename.'.'.$extension;

            if (file_exists($path_file)) {
                return  Yii::$app->response->sendFile($path_file, $filename);
            }
            else {
                echo "Sorry but this build doesn't exist (anymore.) If you think this is an error, please contact us.";
                //return $this->render('error');
            }
        }
        else {
            echo 'Error 404. Are you trying aleatory links?';
            //return $this->render('error');
        }
    }

    public function actionLostbuilds(){
        $dir = '/home/davidsouto/www/'.Yii::$app->params["DOWNLOAD_BUILD_DIR"] ;
        $files = scandir($dir);
        //print_r($files);
        foreach ($files as $f) {
            $ext = pathinfo($f, PATHINFO_EXTENSION);
            //echo $ext.'<br>';
            if ($ext == 'apk' || $ext == 'ipa'){

                $build = Builds::find()->where(" buiFile = '/$f' ")->one();
                //print_r($build);

                if (empty($build)) {
                    echo $build->buiId . ' - ' . $build->buiName . ' - ' . $f . ' <br>';
                    $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
                    @unlink($path_file);
                }
                /*
                else {
                    echo $build->buiId . ' - ' . $build->buiName . ' - ' . $f . ' <br>';
                }
                */
            }
        }
    }

}
