<?php

namespace backend\controllers;

use Yii;
use backend\models\Mobgenners;
use backend\models\MobgennersSearch;
use backend\models\SignupForm;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use backend\models\Builds;

/**
 * MobgennersController implements the CRUD actions for Mobgenners model.
 */
class MobgennersController extends CController
{
    /**
     * @inheritdoc
     */
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
            $roleId = User::getUserIdRole();

            if ($roleId == 1 || $roleId == 12)
                return true;
            else {
                if (($this->action->id == 'index')  || ($this->action->id == 'create') || ($this->action->id == 'delete')){
                    throw new MethodNotAllowedHttpException('You don\'t have permission to see this content.');
                }
                else if (($this->action->id == 'view') || ($this->action->id == 'update')) {

                    $mobgenner = Mobgenners::find()->where(['user'=>Yii::$app->user->identity->id])->one();
                    if ($_GET['id'] != $mobgenner->id) {
                        $this->redirect('/mobgenners/'.$mobgenner->id);
                    }
                    /*
                    $permission = $this->action->controller->id . '_' . $this->action->id;
                    $hasPermission = Permissions::find()->hasPermission($permission);

                    if ($hasPermission == 0) {
                        throw new MethodNotAllowedHttpException('You don\'t have permission to see this content.');
                    }
                    */
                    if (!isset($_SESSION['skin-color'])) {
                        $_SESSION['skin-color'] = 'skin-blue';
                    }
                }
            }
            return true;
        }
        else {
            $this->redirect('/site/logout');
        }

    }


    /**
     * Lists all Mobgenners models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MobgennersSearch();
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
     * Displays a single Mobgenners model.
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
     * Creates a new Mobgenners model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mobgenners();
        $user = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {

            if ($model->save()) {
                $user->first_name = $model->first_name;
                $user->last_name = $model->last_name;
                $user->username = substr($model->email, 0, strrpos($model->email, '@'));
                $user->email = $model->email;
                $user->password = $_POST['SignupForm']['password'];
                $user->status = $_POST['SignupForm']['status'];
                $user->role_id = $_POST['SignupForm']['role_id'];
                if ($newuser = $user->signup()) {

                    $model->user = $newuser->id;
                    $model->save();

                    //Send email
                    if ($user->status == 1 && $_POST['SignupForm']['sendEmail']) {
                        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Mobgenner and user created correctly.'));

                        $sendTo = $user->email;
                        $subject = Yii::t('app', 'New user OTAShare - MOBGEN');

                        $sendEmail = Yii::$app->mailer->compose('newUser', [
                            'user' => $user])
                            ->setFrom(['otashare@mobgen.com' => 'OTAShare - MOBGEN'])
                            ->setTo($sendTo)
                            ->setSubject($subject)
                            ->send();
                    }

                    return $this->redirect(['view', 'id' => $model->id]);

                }
                else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Mobgenner created but user didn\'t create.'));
                    return $this->render('update', [
                        'model' => $model,
                        'user' => $user,
                    ]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $user,
        ]);

    }

    /**
     * Updates an existing Mobgenners model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$user = new SignupForm();
        $user = User::findOne($model->user);

        if (isset($_POST['User'])) {
            $_POST['User']['first_name'] = $_POST['Mobgenners']['first_name'];
            $_POST['User']['last_name'] = $_POST['Mobgenners']['last_name'];
            $user->attributes = $_POST['User'];
            $user->save();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('update', [
                'model' => $model,
                'user' => $user,
            ]);
        }
    }

    /**
     * Deletes an existing Mobgenners model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mobgenners model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mobgenner sthe loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mobgenners::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionFileupload()
    {
        $mobgennerId = Yii::$app->request->post('mobgennerId');
        $timestamp = Yii::$app->request->post('timestamp');

        $model = $this->findModel($mobgennerId);

        if ($model) {
            //echo '<pre>'; print_r($_FILES);echo '</pre>';//die;
            if (!empty($_FILES)) {
                if ($_FILES['mobgennerFile']['error'][0] === UPLOAD_ERR_OK) {

                    // Device OS
                    $extension = strtolower(Builds::_GetExtension($_FILES['mobgennerFile']['name'][0]));

                    if ($extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "gif") {
                        $validExtension = true;
                    } else {
                        $validExtension = false;
                    }

                    if ($validExtension) {
                        //Temporal name
                            $safe = Builds::_GenerateSafeFileName($mobgennerId);
                            //$filename = Yii::getAlias('@webroot') . Yii::$app->params["TEMP_BUILD_DIR"] . $safe . "." . $extension;
                            $filename = Yii::$app->params["BACKEND_WEB"] . "files/mobgenners/" . $safe . "." . $extension;
                        //echo $filename; die;

                        if (move_uploaded_file($_FILES['mobgennerFile']['tmp_name'][0], $filename)) {
                            $model->image = $safe . "." . $extension;
                            $model->save();
                            $data = array('1' => 'Done');
                            return json_encode($data);
                        }
                        //echo '<pre>'; print_r($safe);echo '</pre>';//die;
                    } else {
                        $error = "File extension not recognized as valid extension.";
                    }
                } else {
                    $error = "Error with uploading file.";// . $_FILES['mobgennerFile']['error'];
                }
            }
            else {
                $data = array('1' => 'Nothing to do.');
                return json_encode($data);
            }
        }
    }

    public function actionFileremove()
    {
        $mobgennerId = (int)\Yii::$app->request->post('key');

        $model = $this->findModel($mobgennerId);
        if ($model) {
            if ($model->image != '') {
                $filename = Yii::$app->params["BACKEND_WEB"] . "files/mobgenners/" . $model->image;
                unlink($filename);
                $model->image = '';
                $model->save();
                $data = array('1' => 'Done');
                return json_encode($data);
            } else {
                $data = array('1' => 'Nothing to do.');
                return json_encode($data);
            }
        }
    }
}
