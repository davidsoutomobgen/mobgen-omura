<?php

namespace backend\controllers;

use Yii;
use backend\models\Type;
use backend\models\TypeSearch;
use backend\models\Permissions;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * TypeController implements the CRUD actions for Type model.
 */
class TypeController extends CController
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
                if ((($permission == 'type_index') || ($permission == 'type_create') || ($permission == 'type_delete')) && (($userIdRole == Yii::$app->params['QA_ROLE']) || ($userIdRole == Yii::$app->params['CLIENT_ROLE']))) {
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
     * Lists all Type models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Type model.
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
     * Creates a new Type model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Type();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Type model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $model->image_logo = UploadedFile::getInstance($model, 'image_logo');

            $valid = $model->validate();
            //echo $model->image_logo;die;

            if (!empty($model->image_logo)) {
                //echo '<pre>';print_r($model);echo '</pre>';die;

                //echo $model->image_logo;die;
                // file is uploaded successfully
                $model->logo = $model->upload();
                $model->image_logo->tempName = $model->logo;
            }

            //echo '<pre>';print_r($model);echo '</pre>';die;
            //var_dump($valid); die;
            $model->save();


            if ($valid  &&  $model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Type model.
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
     * Finds the Type model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Type the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Type::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
