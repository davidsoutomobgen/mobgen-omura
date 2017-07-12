<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use backend\models\SignupForm;
use backend\models\PasswordForm;
use backend\models\Mobgenners;
use backend\models\Login;
use backend\models\Client;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends CController
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
            $roleId = User::getUserIdRole();

            if ($roleId != Yii::$app->params['ADMIN_ROLE']) {
                if (($this->action->id == 'index')  || ($this->action->id == 'create') || ($this->action->id == 'delete')){
                    //$this->redirect('/site/logout');
                    throw new MethodNotAllowedHttpException('You don\'t have permission to see this content.');
                }
                else if (($this->action->id == 'view') || ($this->action->id == 'update') || ($this->action->id == 'profile')) {

                    if ($roleId == Yii::$app->params['CLIENT_ROLE'])
                        $mobgenner = Client::find()->where(['user'=>Yii::$app->user->identity->id])->one();
                    else
                        $mobgenner = Mobgenners::find()->where(['user'=>Yii::$app->user->identity->id])->one();

                    //print_r($mobgenner->attributes);die;
                    if ($_GET['id'] != $mobgenner->user) {
                        $this->redirect('/user/profile/'.$mobgenner->user);
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/mobgenners']);
        /*
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        */
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /*
         * CHECK THAT OTHER USER DIDN'T SEE THIS
        $model = new PasswordForm;
        $modeluser = User::find()->where([
            'username'=>Yii::$app->user->identity->username
        ])->one();
        return $this->render('changepassword',[
            'model'=>$model
        ]);
        */
        $userid = Yii::$app->user->identity->id;
        //echo $userid;die;
        if ($userid == $id) {
            $modelpass = new PasswordForm;
            $modeluser = User::find()->where(['id' => $id])->one();
            $mobgenner = Mobgenners::find()->where(['user' => $id])->one();

            return $this->render('view', [
                'modelpass' => $modelpass,
                'model' => $this->findModel($id),
                'user' => $modeluser,
                'mobgenner' => $mobgenner,
            ]);
        }
        else {
            return $this->redirect(['/site']);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm();
        //$authItems = AuthItem::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            //echo '<pre>'; print_r($model->attributes); echo '</pre>'; die;

            if ($user = $model->signup()) {
                return $this->redirect(['/user']);
                /*
                if (Yii::$app->getUser()->login($user)) {
                    //return $this->goHome();
                    return $this->redirect(['/dashboard']);
                }
                */
            }
        }

        return $this->render('signup', [
            'model' => $model,
            //'authItems'=> $authItems,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        $modelpass = new PasswordForm;
        $user = User::find()->where(['id'=>$id])->one();
        if($modelpass->load(Yii::$app->request->post())){
            if($modelpass->validate()){
                try{
                    $user->setPassword($_POST['PasswordForm']['newpass']);
                    $user->generateAuthKey();
                    if($user->save()){
                        Yii::$app->getSession()->setFlash(
                            'success','Password changed'
                        );
                        return $this->redirect(['/user/' . $user->id]);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error','Password not changed'
                        );
                        return $this->redirect(['/user/' . $user->id]);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('changepassword',[
                        'modelpass'=>$modelpass,
                        'model' => $this->findModel($id),
                        'user'=>$user,
                    ]);
                }
            }
            /*
            else {
                echo '<pre>'; print_r($modelpass->getErrors()); echo '</pre>';
                echo 'aki2'; die;
            }
            */
        }
        //$model = new SignupForm();
        $mobgenner = Mobgenners::find()->where(['user'=>$id])->one();

        return $this->render('view',[
            'modelpass'=>$modelpass,
            'model' => $this->findModel($id),
            'user'=>$user,
            'mobgenner' => $mobgenner,
        ]);

    }


    public function actionProfile($id){

        $modelpass = new PasswordForm;
        $user = User::find()->where(['id'=>$id])->one();
        //echo '<pre>'; print_r($user); echo '</pre>'; die;
        $roleId = User::getUserIdRole();
        if ($roleId == Yii::$app->params['CLIENT_ROLE'])
            $mobgenner = Client::find()->where(['user'=>$id])->one();
        else
            $mobgenner = Mobgenners::find()->where(['user'=>$id])->one();

        if($modelpass->load(Yii::$app->request->post())){
            if($modelpass->validate()){
                try{
                    $user->setPassword($_POST['PasswordForm']['newpass']);
                    $user->generateAuthKey();
                    if($user->save()){
                        Yii::$app->getSession()->setFlash(
                            'success','Password changed'
                        );
                        return $this->redirect(['/user/' . $user->id]);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error','Password not changed'
                        );
                        return $this->redirect(['/user/' . $user->id]);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('changepassword',[
                        'modelpass'=>$modelpass,
                        'model' => $this->findModel($id),
                        'user'=>$user,
                    ]);
                }
            }
            /*
            else {
                echo '<pre>'; print_r($modelpass->getErrors()); echo '</pre>';
                echo 'aki2'; die;
            }
            */
        }
        return $this->render('view',[
            'modelpass'=>$modelpass,
            'user' => $user,
            'model' => $this->findModel($id),
            'mobgenner' => $mobgenner,
        ]);

    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Displays the permission of this user
     * @param integer $id
     * @return mixed
     */
    public function actionLink($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
