<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use backend\models\AuthItem;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Cypher\Query;

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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAbsences()
    {
        /*
         * Set up the neo4j connection
         */
        $neo4j = new Client();
        $neo4j->getTransport()->setAuth('neo4j','none');

//        $queryTemplate = "MATCH (n:AbsenceEvent)-[:EVENT_FOR]->(u:User) RETURN n,u ORDER BY n.effective_from";
        $queryTemplate = "MATCH (n:AbsenceEvent {absence_type_name: 'Holiday'})-[:EVENT_FOR]->(u:User)".
                        " WHERE (n.effective_to >= '2016-07-20T00:00:00') AND (n.effective_from <= '2016-08-20T00:00:00')".
                        " RETURN n,u".
                        " ORDER BY n.effective_from";
        $cypher = new Query($neo4j, $queryTemplate);
        $results = $cypher->getResultSet();
        $ncnt = count($results);

//      $nodes = array('dataset' => 'locations', 'count' => $ncnt, 'status' => 'ok', 'data' => array());
        $eventdata = array();
        foreach ($results as $row) {
            $eventdata[] = array(
//                'person_absence_event_guid' => $row['n']->person_absence_event_guid,
                'person_code' => $row['n']->person_code,
                'person_name' => $row['u']->name,
                'org_unit_code' => $row['u']->org_unit_code,

                'absence_reason' => $row['n']->absence_reason,
                'absence_status' => $row['n']->absence_status,
                'effective_from' => $row['n']->effective_from,
                'effective_to' => $row['n']->effective_to,

                'absence_plan_type_name' => $row['n']->absence_plan_type_name,
                'absence_type_name' => $row['n']->absence_type_name,
                'absence_category' => $row['n']->absence_category,

                'commences_code' => $row['n']->commences_code,
                'commences' => $row['n']->commences,
                'finishes_code' => $row['n']->finishes_code,
                'finishes' => $row['n']->finishes,

                'time_units' => $row['n']->time_units,
                'total_by_time_unit' => $row['n']->total_by_time_unit,

                'total_days' => $row['n']->total_days,
                'total_working_time_taken_by_time_unit' => $row['n']->total_working_time_taken_by_time_unit,
                'absence_event_total_working_hours' => $row['n']->absence_event_total_working_hours,
            );
        }

        return $this->render('absences', ['data' => $eventdata]);
    }

    public function actionLogin()
    {
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

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        $authItems = AuthItem::find()->all();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'authItems'=> $authItems,
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
