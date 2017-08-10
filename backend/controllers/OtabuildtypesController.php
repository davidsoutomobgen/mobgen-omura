<?php

namespace backend\controllers;

use backend\models\OtaProjects;
use backend\models\OtaProjectsBuildtypes;
use Yii;
use backend\models\OtaBuildTypes;
use backend\models\OtaBuildTypesSearch;
use backend\models\Permissions;
use common\models\User;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OtabuildtypesController implements the CRUD actions for OtaBuildTypes model.
 */
class OtabuildtypesController extends CController
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
            if (($this->action->id == 'index') || ($this->action->id == 'view') || ($this->action->id == 'create') || ($this->action->id == 'update') || ($this->action->id == 'delete')) {
                $permission = $this->action->controller->id.'_'.$this->action->id;
                $hasPermission = Permissions::find()->hasPermission($permission);
                $userIdRole = User::getUserIdRole();
                //echo $permission;die;
                if (($hasPermission == 0) || (($userIdRole == Yii::$app->params['QA_ROLE']) || ($userIdRole == Yii::$app->params['CLIENT_ROLE']))) {
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
     * Lists all OtaBuildTypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OtaBuildTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (\Yii::$app->devicedetect->isMobile())
            $view = 'indexmobile';
        else
            $view = 'index';

        return $this->render($view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OtaBuildTypes model.
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
     * Creates a new OtaBuildTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OtaBuildTypes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OtaBuildTypes model.
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
     * Deletes an existing OtaBuildTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $used = OtaProjectsBuildtypes::find()->where('id_ota_buildtypes = :id', ['id'=>$id])->one();
        if (!empty($used)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Build Type didn\'t remove because is used in projects.'));
        }
        else {
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Build Type removed correctly.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the OtaBuildTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OtaBuildTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OtaBuildTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
