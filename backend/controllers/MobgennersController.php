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

/**
 * MobgennersController implements the CRUD actions for Mobgenners model.
 */
class MobgennersController extends Controller
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

            if ($roleId != 1 && $roleId != 12) {
                //print_r($this->action->id);die;
                if (($this->action->id == 'index')  || ($this->action->id == 'create') || ($this->action->id == 'delete')){
                    $this->redirect('/site/logout');
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

        return $this->render('index', [
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
        //$user = new User();
        $user = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            //echo '<pre>'; print_r($_POST); echo '</pre>';die;


            //$sufix = substr($model->email, strrpos($model->email, '@') + 1, strlen($model->email));
            //echo $user->username.'<br>';
            //echo $sufix.'<br>';


            if ($model->save()) {

                //if ($user->signup()) {
                //echo '<pre>'; print_r($user); echo '</pre>';
                $user->first_name = $model->first_name;
                $user->last_name = $model->last_name;
                $user->username = substr($model->email, 0, strrpos($model->email, '@'));
                $user->email = $model->email;
                $user->password = $_POST['SignupForm']['password'];
                $user->status = $_POST['SignupForm']['status'];
                $user->role_id = $_POST['Mobgenners']['role_id'];

                if ($user->signup()) {
                    //if ($user->save()) {
                        $model->user = $user->id;
                        $model->save();
                        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Mobgenner and user created correctly.'));
                    /*}
                    else {
                        echo 'USER<pre>'; print_r($user->getErrors()); echo '</pre>';die;
                        Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Mobgenner created but user didn\'t create.'));

                    }
                    */
                    //Send email
                    if ($user->status == 1) {
                        $sendTo = 'david.souto@mobgen.com'; //$user->email;
                        $subject = Yii::t('app', 'New user OTAShare - MOBGEN');
                        $mail = Yii::t('app', 'Hello, {name}! <br />', [
                            'name' => $user->first_name,
                        ]);
                        $mail .= Yii::t('app', 'Welcome to the OTAShare.<br />User: {username} <br />Password: {password}', [
                            'username' => $user->username,
                            'password' => $user->password
                        ]);
                        $mail .= Yii::t('app', '<br />Change your password in your profile.<br />Greetings.<br />MOBGEN');

                        $sendEmail = Yii::$app->mailer->compose()
                            ->setFrom(['otashare@mobgen.com' => 'OTAShare - MOBGEN'])
                            ->setTo($sendTo)
                            ->setSubject($subject)
                            ->setHtmlBody($mail)
                            ->send();
                    }
                    else{
                        Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Mobgenner created but user didn\'t create.'));
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                    //}
                    //echo '<pre>'; print_r($user); echo '</pre>';
                    //die;
                }
                else {
                    //echo 'USER<pre>'; print_r($user->getErrors()); echo '</pre>'; //die;
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Mobgenner created but user didn\'t create.'));
                    return $this->render('update', [
                        'model' => $model,
                        'user' => $user,
                    ]);
                }
            }
        }
        //echo '<pre>'; print_r($model->attributes); echo '</pre>';
        //echo '<pre>'; print_r($user->attributes); echo '</pre>';
       /* if (!empty($model->getErrors()) || !empty($user->getErrors())) {

            echo 'MODEL<pre>';  print_r($model->getErrors()); echo '</pre>';
            echo '<pre>'; print_r($model->attributes); echo '</pre>';
            echo 'USER<pre>'; print_r($user->getErrors()); echo '</pre>';
            echo '<pre>'; print_r($user->attributes); echo '</pre>';
            die;
        }
       */
        //getErrors

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
        $user = new SignupForm();
        //$model = new PasswordForm;
        $user = User::find()->where(['id'=>$model->user])->one();
       // print_r($user->attributes);die;
        
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
}