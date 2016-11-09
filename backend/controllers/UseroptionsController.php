<?php

namespace backend\controllers;

use backend\models\Options;
use Yii;
use backend\models\UserOptions;
use backend\models\UserOptionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UseroptionsController implements the CRUD actions for UserOptions model.
 */
class UseroptionsController extends Controller
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
     * Lists all UserOptions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserOptionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserOptions model.
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
     * Creates a new UserOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserOptions();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserOptions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //var_dump($_POST); die;
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing UserOptions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateajax()
    {        

        $option = Options::find()
            ->where('variable = :option', [':option' => Yii::$app->request->post('option')])
            ->one();

        if (!empty($option->id)) {
            $id_option = $option->id;
            $id_user = \Yii::$app->user->identity->id;

            //$model = UserOptions::find()->getVariable(10, 'fixed-header');
            $model = UserOptions::find()
                ->where('id_option = :option', [':option' => $id_option])
                ->andWhere('id_user = :id_user', [':id_user' => $id_user])
                ->one();

            if (empty($model))
                $model = new UserOptions();

            $_POST["UserOptions"]["id_user"] = $id_user;
            $_POST["UserOptions"]["id_option"] = $id_option;
            $_POST["UserOptions"]["value"] = Yii::$app->request->post('value');

            $model->load($_POST);

            //var_dump($model);die;

            $model->save();

            echo '1';
        }
        else
            echo '0';
        
        //return false;
    }


    /**
     * Deletes an existing UserOptions model.
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
     * Finds the UserOptions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserOptions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserOptions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
