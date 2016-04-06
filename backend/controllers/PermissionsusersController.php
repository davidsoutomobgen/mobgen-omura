<?php

namespace backend\controllers;

use Yii;
use backend\models\PermissionsUsers;
use backend\models\PermissionsUsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PermissionsusersController implements the CRUD actions for PermissionsUsers model.
 */
class PermissionsusersController extends Controller
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

    /**
     * Lists all PermissionsUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermissionsUsersSearch();
        //echo '<pre>'; print_r(Yii::$app->request->queryParams); echo '</pre>';die;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermissionsUsers model.
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
     * Creates a new PermissionsUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PermissionsUsers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PermissionsUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PermissionsUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionActive($id) {

        $model = $this->findModel($id);
        if ($model->state == 1)
            $model->state = 0;
        else
            $model->state = 1;

        $model->save();
        //Version ajax doesn't works
        if (Yii::$app->getRequest()->isAjax) {
            $dataProvider = new ActiveDataProvider([
                'query' => PermissionUsers::find(),
                'sort' => false
            ]);
            return $this->renderPartial('setup', [
                'dataProvider' => $dataProvider
            ]);
        }
        return $this->redirect(['setup', 'id'=>$model->id_user]);
    }




    /**
     * Finds the PermissionsUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PermissionsUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermissionsUsers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSetup($id)
    {
        $searchModel = new PermissionsUsersSearch();

        //echo '<pre>'; print_r(Yii::$app->request->queryParams); echo '</pre>';die;
        $data['PermissionsUsersSearch']['id_user'] = $id;


        $dataProvider = $searchModel->search($data);

        return $this->render('setup', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}