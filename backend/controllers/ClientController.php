<?php

namespace backend\controllers;

use Yii;
use backend\models\Client;
use backend\models\ClientSearch;
use backend\models\SignupForm;
use backend\models\Permissions;
use common\models\User;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends Controller
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
                if ((($permission == 'client_index') || ($permission == 'client_delete')) && (($userIdRole == Yii::$app->params['QA_ROLE']) || ($userIdRole == Yii::$app->params['CLIENT_ROLE']))) {
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
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = Client::find()->with('project')->where('id = :idClient',  [':idClient' => $id])->all();

        if ((isset($model[0])) && ($model[0]->user == Yii::$app->user->identity->id))
            return $this->render('view', [
                //'model' => $this->findModel($id),
                'model' => $model[0],
            ]);
        else
            $this->redirect('/site');

    }

    /**
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();
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
                        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Client and user created correctly.'));

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
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Client created but user didn\'t create.'));
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $user,
        ]);


    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $roleId = User::getUserIdRole();

        if ($roleId == Yii::$app->params['CLIENT_ROLE']) {
            $client = Client::find()->where(['id'=>$id])->one();
            //echo '<pre>'; print_r($client); echo '</pre>'; die;
            if ((!isset($client)) || (Yii::$app->user->identity->id != $client->user))
                return $this->redirect(['/user/profile/' . Yii::$app->user->identity->id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->user0 == null) {
                $user = new SignupForm();
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
                        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Client and user updated correctly.'));

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
                    Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Client updated but user didn\'t create.'));
                    return $this->render('update', [
                        'model' => $model,
                        'user' => $user,
                    ]);
                }
            } else {
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
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
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
