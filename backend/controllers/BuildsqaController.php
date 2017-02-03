<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\BuildsQa;
use backend\models\BuildsQaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BuildsQaController implements the CRUD actions for BuildsQa model.
 */
class BuildsqaController extends Controller
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

    /**
     * Lists all BuildsQa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BuildsQaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BuildsQa model.
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
     * Creates a new BuildsQa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BuildsQa();

        if ($model->load(Yii::$app->request->post())) {
            //$model->c = date('Y-m-d h:m:s');
            if($model->save())
            {
                //return $this->redirect(['/buildsqa/view', 'id' => $model->buildsqa]);
                return $this->redirect(['/builds/'.$model->buiId]);
            }
            else
            {
                print_r($model->getErrors());
                echo 'error haciendo save ';die;
                echo 0;
            }
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }                  
    }

    /**
     * Updates an existing BuildsQa model.
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
     * Creates a new BuildsQa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionQa($id)
    {

        $model = new BuildsQa();

        if ($model->load(Yii::$app->request->post())) {                
            $model->buiId = $id;
            $model->created_by = Yii::$app->user->identity->id;
            if($model->save())
            {                
                //print_r($model->bui->buiProIdFK);die;
                return $this->redirect(['/otaprojects/'.$model->bui->buiProIdFK]);
            }
            else
            {
                print_r($model->getErrors());
                echo 'error haciendo save ';die;
                echo 0;
            }
        } else {
            $idRole = User::getUserIdRole();
            
            $params['BuildsQaSearch']['buiId'] =  $id;

            $searchBuilds = new BuildsQaSearch();
            $dataProvider = $searchBuilds->search($params);

            if ($idRole == 11) {
                return $this->renderAjax('create', [
                    'model' => $model,
                    'searchModel' => $searchBuilds,
                    'dataProvider' => $dataProvider,
                ]);
            }
            else {                            
                return $this->renderAjax('index', [                    
                    'searchModel' => $searchBuilds,
                    'dataProvider' => $dataProvider,
                ]);
                /*
                return $this->renderAjax('state', [
                    'model' => $model,
                ]);
                */
            }

            
        }

    }

    /**
     * Deletes an existing BuildsQa model.
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
     * Finds the BuildsQa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BuildsQa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BuildsQa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
