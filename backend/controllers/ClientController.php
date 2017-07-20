<?php

namespace backend\controllers;

use Yii;
use backend\models\Client;
use backend\models\ClientSearch;
use backend\models\SignupForm;
use yii\web\Controller;
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

        return $this->render('view', [
            //'model' => $this->findModel($id),
            'model' => $model[0],
        ]);
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
