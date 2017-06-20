<?php
namespace backend\controllers;

use backend\models\UserOptions;
use backend\models\UserOptionsSearch;
use backend\models\UserOptionsQuery;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Session;
use yii\web\BadRequestHttpException;

use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class SiteController extends CController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'logout', 'error', 'forgotpassword', 'resetpassword'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'time', 'date'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            */
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionIndex()
    {
        $userid = Yii::$app->user->identity->id;
        //$variable = 'fixed-header';
        //$model = UserOptions::find()->getVariable($userid, 'fixed_header');


        $options = UserOptions::find()->getUserOptionsByUserId((int)$userid);
        $session = Yii::$app->session;
        foreach ($options as $opt) {
            if ($opt['type'] == 'integer')
                $session->set($opt['variable'], (int)$opt['value']);
            else if ($opt['type'] == 'string')
                $session->set($opt['variable'], (string)$opt['value']);
        }

        $roleId = User::getUserIdRole();
        if ($roleId == Yii::$app->params['CLIENT_ROLE']) {
            if (\Yii::$app->devicedetect->isMobile())
                $view = 'indexmobile_client';
            else
                $view = 'index_client';
        }
        else {
            if (\Yii::$app->devicedetect->isMobile())
                $view = 'indexmobile';
            else
                $view = 'index';
        }

        $user = User::find()->where(['id'=>$userid])->one();


        return $this->render($view, ['user'=>$user, 'response' => date('Y-m-d H:i:s')]);

    }

    public function actionLogin()
    {
        $this->layout = 'loginLayout';

        $model = new LoginForm();
        $userPost = Yii::$app->request->post();
        if (isset($userPost['LoginForm']['username'])) {
            $user = User::find()->where(['email' => $userPost['LoginForm']['username']])->one();
            if ($user) {
                $userPost['LoginForm']['username'] = $user->username;
            }
        }
        if ($model->load($userPost) && $model->login()) {
            if (isset($_GET['back'])) {
                return $this->redirect($_GET['back']);
            }
            return $this->redirect('/site');
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        $url = '/site/login';

        if (isset($_GET['back'])) {
            $url = "{$url}?back={$_GET['back']}";
        }

        return $this->redirect($url);
    }

    public function actionTime()
    {
        return $this->render('index', ['response' => date('H:i:s')]);
    }

    public function actionDate()
    {
        return $this->render('index', ['response' => date('Y-M-d')]);
    }


    public function actionForgotpassword()
    {
        $this->layout = 'loginLayout';

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post())) {

            $modelReset = new PasswordResetRequestForm();
            $user = $model->getUser();

            if (isset($user->email) && ($_POST['LoginForm']['username'] != $user->email))
                $modelReset->email = $user->email;
            else
                $modelReset->email = $_POST['LoginForm']['username'];

            if ($modelReset->validate()) {
                if ($modelReset->sendEmail()) {
                    Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');
                    return $this->goHome();
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
                }
            }
            else{
                //print_r($modelReset->getErrors());die;
                return $this->render('forgotpassword', [
                    'model' => $model,
                ]);
            }
        }
        else {
            //echo 'e2e';die;

            return $this->render('forgotpassword', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetpassword($token)
    {
        $this->layout = 'loginLayout';

        try {
            $model = new ResetPasswordForm($token);

        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        //$model = new ResetPasswordForm($token);
        //print_r($model->attributes);//die;



        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');
            return $this->goHome();
        }

        return $this->render('resetpassword', [
            'model' => $model,
        ]);
    }

}
