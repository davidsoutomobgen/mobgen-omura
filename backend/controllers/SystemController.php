<?php

namespace backend\controllers;

use Yii;
use backend\models\System;
use backend\models\SystemSearch;
use backend\models\Permissions;
use backend\models\Builds;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\filters\VerbFilter;


/**
 * SystemController implements the CRUD actions for System model.
 */
class SystemController extends Controller
{
    /**
     * @inheritdoc
     */
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
            $permission = $this->action->controller->id.'_'.$this->action->id;
            $hasPermission = Permissions::find()->hasPermission($permission);
            //echo $permission.' -- ' . $hasPermission . ' <br>';die;
            if ($hasPermission == 0) {
                throw new MethodNotAllowedHttpException('You don\'t have permission to see this content.');
            }
            if (!isset($_SESSION['skin-color'])) {
                $_SESSION['skin-color'] = 'skin-blue';
            }
            return true;
        }
        else {
            $this->redirect('/site/logout');
        }
    }

    /**
     * Lists all System models.
     * @return mixed
     */
    public function actionIndex()
    {
        $id = 1;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single System model.
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
     * Creates a new System model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $id = 1;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing System model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $id = 1;
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
     * Deletes an existing System model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $id = 1;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the System model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return System the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = System::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRemovebuilds()
    {
        if (isset(Yii::$app->user->identity->id)) {
            $userid = Yii::$app->user->identity->id;
            if ($userid == 1) {
                $userid = Yii::$app->user->identity->id;
                if (isset($_POST['optionsRadios']) && ($_POST['optionsRadios'] > 0) && ($_POST['optionsRadios'] < 4)) {
                    //if (isset($_POST)) {
                    //print_r($_POST);
                    $date = $_POST['date_'.$_POST['optionsRadios']];
                    //print_r($date);
                    $builds = Builds::find()
                                ->where('updated_at < :date AND buiFav = :fav AND buiStatus = :status', [':date' => $date, ':fav' => 0, ':status' => 0])
                                ->all();

                    $count = 0;
                    foreach ($builds as $build) {
                        //echo '<pre>'; print_r($build->attributes); echo '</pre>';
                        //echo $build->buiFile;
                        $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] . $build->buiFile;
                        @unlink($path_file);
                        $build->buiStatus = '9';
                        $build->save(false);
                        $count++;
                    }
                }
                return $this->render('removebuilds');

            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        else {
            $this->redirect('/site/logout');
        }
    }
}
