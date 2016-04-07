<?php

namespace backend\controllers;

use Yii;
use backend\models\OtaProjects;
use backend\models\OtaProjectsSearch;
use backend\models\Builds;
use backend\models\BuildsSearch;
use backend\models\OtaBuildTypes;
use backend\models\OtaProjectsBuildtypes;
use backend\models\Permissions;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OtaprojectsController implements the CRUD actions for OtaProjects model.
 */
class OtaprojectsController extends Controller
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
            
            $permission = $this->action->controller->id.'_'.$this->action->id;
            $hasPermission = Permissions::find()->hasPermission($permission);
            //echo $permission.'<br>';die;
            if ($hasPermission == 0) {
                throw new MethodNotAllowedHttpException('You don\'t have permission to see this content.');
            }                    
        } 
        return true;
    }


    /**
     * Lists all OtaProjects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OtaProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OtaProjects model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $params = Yii::$app->request->queryParams;
        //echo '<pre>'; print_r($params);echo '</pre>';

        $params['BuildsSearch']['buiProIdFK'] =  $id;

        $searchBuilds = new BuildsSearch();
        $dataProvider = $searchBuilds->search($params);
        //$dataProvider = $searchBuilds->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchBuilds' => $searchBuilds,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new OtaProjects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OtaProjects();

        $otaBuildTypes = OtaBuildTypes::find()->all();
        foreach ($otaBuildTypes as $buildtypes) {
            $data[$buildtypes['id']] =  $buildtypes['name'];
        }

        $selected = array();
        $value = array();

        if ($model->load(Yii::$app->request->post())) {

            $model->safename = $this->_GenerateSafeFileName($model->name);
            $model->proHash = $this->_GenerateHash();
            $model->proAPIKey = $this->_GenerateHash();
            $model->proAPIBuildKey = $this->_GenerateSecureApiHash($model->name);

            if ($model->save()) {
                $post = Yii::$app->request->post();
                if (isset($post['proBuildType'])) {
                    foreach ($post['proBuildType'] as $tt){
                        if (empty(intval($tt))) {                           
                            $exist = OtaBuildTypes::find()->where('name LIKE :name')->addParams([':name'=>$tt])->one();
                            if (isset($exist)) {
                                $tt = $exist->id;
                            } else {
                                $buildtypes = new OtaBuildTypes;
                                $buildtypes->name = $tt;
                                $buildtypes->save();                            
                                $tt = $buildtypes->id;
                            }
                        }

                        $aux = new OtaProjectsBuildtypes();
                        $aux->id_ota_project = $model->id;
                        $aux->id_ota_buildtypes = $tt;
                        $aux->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'select_buildtype' => $selected,
                    'value' => $value,
                    'ota_buildtypes' => $data,
                ]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
                'select_buildtype' => $selected,
                'value' => $value,
                'ota_buildtypes' => $data,
            ]);
        }
    }

    /**
     * Updates an existing OtaProjects model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $otaBuildTypes = OtaBuildTypes::find()->all();
        foreach ($otaBuildTypes as $buildtypes) {
            $data[$buildtypes['id']] =  $buildtypes['name'];
        }

        $selected = OtaProjectsBuildtypes::find()->with('idOtaProject')->with('idOtaBuildtypes')->where('id_ota_project = :idotaproject',  [':idotaproject' => $id])->all();

        if (!empty ($selected)) {
            foreach ($selected as $sel)
                $value[] = (int)$sel->idOtaBuildtypes->id;
        }
        else $value = array();

        //$value = array();

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
        if ($model->load(Yii::$app->request->post())) {

            $this->_process($model);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'select_buildtype' => $selected,
                'value' => $value,
                'ota_buildtypes' => $data,
            ]);
        }
    }


    private function _process($model) {

        $post = Yii::$app->request->post();
        //echo '<pre>';var_dump($post);echo '</pre>';die;

        if(isset($post['OtaProjects']))
        {
            $model->attributes=$_POST['OtaProjects'];
            if($model->save()){
                //echo 'aki';die;
                OtaProjectsBuildtypes::deleteAll(['id_ota_project' => $model->id ]);

                //OtaProjectsBuildTypes
                if (isset($post['proBuildType'])) {
                    //echo '<pre>';var_dump($post);echo '</pre>';die;
                    foreach ($post['proBuildType'] as $tt){

                        if (empty(intval($tt))) {
                            $buildtypes = new OtaBuildTypes;
                            $buildtypes->name = $tt;
                            $buildtypes->save();

                            $tt = $buildtypes->id;
                        }

                        $aux = new OtaProjectsBuildtypes();
                        $aux->id_ota_project = $model->id;
                        $aux->id_ota_buildtypes = $tt;
                        $aux->save();
                    }
                }
                $this->redirect(array('view','id'=>$model->id));
            }
        }
    }

    /**
     * Deletes an existing OtaProjects model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $builds = Builds::find()->where('buiProIdFK = :build_id',  [':build_id' => $id])->all();

        if (empty($builds)){
            $this->findModel($id)->delete();
            $message = 1;            
        }
        else 
            $message = 2;


        $searchModel = new OtaProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'message' => $message,
        ]);
        //return $this->redirect(['index']);
    }

    /**
     * Finds the OtaProjects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OtaProjects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OtaProjects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected static function _GenerateHash($length=12) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0,strlen($characters) - 1)];
        }

        return $string;
    }

    public static function _GenerateSecureApiHash($projectName, $length=30)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0,strlen($characters) - 1)];
        }

        $hash = "";

        $hash = crypt($projectName . $string, $string.$string);

        for ($p = $length; $p > 0 ; $p--) {
            $string .= $characters[mt_rand(0,strlen($characters) - 1)];
        }

        $hash .= $string;

        return substr($hash, 0, $length);
    }

    public static function _GenerateSafeFileName($text) {
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('/[^-\w]+/', '', $text);
        return $text;
    }

}
