<?php
namespace backend\controllers;

use backend\models\UserOptions;
use backend\models\UserOptionsSearch;
use backend\models\UserOptionsQuery;
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
            //var_dump($opt);
            //echo '<br>';
            if ($opt['type'] == 'integer')
                $session->set($opt['variable'], (int) $opt['value']);
            else if ($opt['type'] == 'string')
                $session->set($opt['variable'], (string) $opt['value']);
        }

        //Yii::$app->view->params['fixed'] = '';

        if ($userid == 1)
            return $this->render('index', ['response' => date('Y-m-d H:i:s')]);
        else
            return $this->redirect('/otaprojects/index');

        //return $this->render('index', ['response' => date('Y-m-d H:i:s')]);
    }

    public function actionLogin()
    {
        $this->layout = 'loginLayout';
        if (!\Yii::$app->user->isGuest) {

            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /*
    public function actionLogin()
    {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');

        //echo '<pre>';print_r($serviceName);echo '</pre>'; die;
        echo '<pre>';var_dump($serviceName);echo '</pre>';


        if (isset($serviceName)) {
            // /** @var $eauth \nodge\eauth\ServiceBase
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            try {
                if ($eauth->authenticate()) {
//                  var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                    // special redirect with closing popup window
                    $eauth->redirect();
                } else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            } catch (\nodge\eauth\ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());

                // close popup window and redirect to cancelUrl
//              $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
    }

*/
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
