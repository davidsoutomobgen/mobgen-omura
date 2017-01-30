<?php

namespace backend\controllers;

use Yii;
use backend\models\Templates;
use backend\models\TemplatesSearch;
use backend\models\Structures;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


/**
 * TemplatesController implements the CRUD actions for Templates model.
 */
class TemplatesController extends Controller
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
     * Lists all Templates models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemplatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Templates model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $modelTemplates = new Templates;
        $modelStructures = [new Structures];
        if ($modelTemplates->load(Yii::$app->request->post())) {

            $modelStructures = Model::createMultiple(Structures::classname());
            Model::loadMultiple($modelStructures, Yii::$app->request->post());

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelStructures),
                    ActiveForm::validate($modelTemplates)
                );
            }
            // validate all models
            $valid = $modelTemplates->validate();
            $valid = Model::validateMultiple($modelStructures) && $valid;

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelTemplates->save(false)) {
                        foreach ($modelStructures as $modelStructures) {
                            $modelStructures->template_id = $modelTemplates->id;
                            if (! ($flag = $modelStructures->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelTemplates->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'modelTemplates' => $modelTemplates,
            'modelStructures' => (empty($modelStructures)) ? [new Structures] : $modelStructures
        ]);
    }

    /**
     * Updates an existing Templates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelTemplates = $this->findModel($id);
        $modelStructures = $modelTemplates->structures; //ERROR

        if ($modelTemplates->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelStructures, 'id', 'id');
            $modelStructures = Model::createMultiple(Structures::classname(), $modelStructures);
            Model::loadMultiple($modelStructures, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelStructures, 'id', 'id')));

            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelStructures),
                    ActiveForm::validate($modelTemplates)
                );
            }

            // validate all models
            $valid = $modelTemplates->validate();
            $valid = Model::validateMultiple($modelStructures) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $modelTemplates->save(false)) {
                        if (! empty($deletedIDs)) {
                            Structures::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelStructures as $modelStructures) {
                            $modelStructures->template_id = $modelTemplates->id;
                            if (! ($flag = $modelStructures->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelTemplates->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'modelTemplates' => $modelTemplates,
            'modelStructures' => (empty($modelStructures)) ? [new Structures] : $modelStructures
        ]);
    }

    /**
     * Deletes an existing Templates model.
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
     * Finds the Templates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Templates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Templates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
