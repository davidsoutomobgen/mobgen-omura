<?php
namespace backend\controllers;

use backend\models\UserOptions;
use backend\models\UserOptionsSearch;
use backend\models\UserOptionsQuery;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Session;


/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'time', 'date'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => \nodge\eauth\openid\ControllerBehavior::className(),
                'only' => array('login'),
            ],
            */
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
        $userid =  Yii::$app->user->identity->id;
        //$variable = 'fixed-header';
        //$model = UserOptions::find()->getVariable($userid, 'fixed_header');

        $options = UserOptions::find()->getUserOptionsByUserId((int) $userid);
        $session = Yii::$app->session;
        foreach ($options as $opt) {
            if ($opt['type'] == 'integer')
                $session->set($opt['variable'], (int) $opt['value']);
            else if ($opt['type'] == 'string')
                $session->set($opt['variable'], (string) $opt['value']);
        }

        if (\Yii::$app->devicedetect->isMobile())
            $view = 'indexmobile';
        else
            $view = 'index';

        $user = User::find()->where(['id'=>$userid])->one();


        return $this->render($view, ['user'=>$user, 'response' => date('Y-m-d H:i:s')]);

    }

    public function actionLogin()
    {
        $this->layout = 'loginLayout';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTime()
    {
        return $this->render('index', ['response' => date('H:i:s')]);
    }

    public function actionDate()
    {
        return $this->render('index', ['response' => date('Y-M-d')]);
    }
}
