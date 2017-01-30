<?php
namespace frontend\controllers;

use Yii;
use backend\models\OtaProjects;
use backend\models\Builds;
use backend\models\BuildsDownloaded;
use backend\models\OtaProjectsBuildtypes;
/*
use backend\models\Templates;
use backend\models\Utils;
*/
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BuildsController implements the CRUD actions for Builds model.
 */
class ProjectController extends Controller
{
    public $send_email;

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
     * Lists all Builds models.
     * @return mixed
     */
    public function actionIndex($hash, $safename)
    {        
        
        $model = OtaProjects::find()->with('otaProjectsBuildtypes')->where("proHash = '$hash' AND safename = '$safename' ")->one();

        if (!empty($model)) {
            //$path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;

            $condition = '';
            $post = Yii::$app->request->post();
            $proBuildTypeSelect = 'all';

            if (!empty($post)) {            
                if ($post['proBuildType'] != 'all') 
                    $condition = ' AND buiBuildType = "'.$post['proBuildType'].'"' ;                
                $proBuildTypeSelect = $post['proBuildType'];
            }   


        
            $builds = Builds::find()->where("buiProIdFK = '$model->id' AND buiVisibleClient = 1 AND buiStatus = 0 ".$condition)->orderBy('buiFav desc, updated_at desc')->all();
            $project = Yii::$app->params["TEMPLATES"] . 'default/project_new.php';

            //CHANGE THIS WHEN TEMPLATES WILL BE ADMiNISTRABLES FROM BACKEND
            /*
            switch ($model->id_ota_template) {
                case 0:
                    $project = Yii::$app->params["TEMPLATES"] . 'default/project_new.php';
                    break;
                case 1:
                    $project =  Yii::$app->params["TEMPLATES"] . 'abnamro/project.php';
                    break;
                case 2:
                    $project = Yii::$app->params["TEMPLATES"] . 'shell-innovation/project.php';
                    break;
                case 3:
                    $project =  Yii::$app->params["TEMPLATES"] . 'redevco/project.php';
                    break;
                case 4:
                    $project = Yii::$app->params["TEMPLATES"] . 'whoiswho_ron/project.php';
                    break;
                case 5:
                    $project = Yii::$app->params["TEMPLATES"] . 'nationalexpress/project.php';
                    break;
            }
            */

            $data = array();
            $otaBuildTypes = OtaProjectsBuildtypes::find()->with('idOtaBuildtypes')->where('id_ota_project = :id_ota_project',  [':id_ota_project' => $model->id])->all();
            foreach ($otaBuildTypes as $buildtypes) {
                $data[$buildtypes->id] =  $buildtypes->idOtaBuildtypes->name;
            }

            return $this->renderFile($project, [
                'project' => $model,
                'builds' => $builds,
                'buildtypes' => $data,
                'proBuildTypeSelect' => $proBuildTypeSelect,
                //'url' => $path_file,
            ]);
        }
        else {
            echo 'Error 404. Are you trying aleatory links?!';
            die;
            return $this->render('error');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionDownload($id) {

        $model = Builds::find()->where(" buiId = $id ")->one();
        //echo "<br> buiHash = '$hash' AND buiSafename = '$safename' <br>";
        //var_dump($model);
        //echo '<pre>'; print_r($model); echo '</pre>';die;

        if (!empty($model)) {
            $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
            //echo $path_file . '  ----  ' . $model->buiVisibleClient . '<br>';die;
            if ($model->buiVisibleClient == 1 && file_exists($path_file)) {
                $downloaded = new BuildsDownloaded();
                $downloaded->buiId = $model->buiId;
                $downloaded->save();
                return  Yii::$app->response->sendFile($path_file);
            }
            else {
                //echo $path_file . '  ----  ' . $model->buiVisibleClient . '<br>';
                if ($model->buiVisibleClient == 0) {
                    echo "This build is not available for public users.";
                    //return $this->render('error');
                } else {

                    echo "Sorry but this build doesn't exist (anymore.) If you think this is an error, please contact us.";
                    //return $this->render('error');
                }
            }
        }
        else {
            echo 'Error 404. Are you trying aleatory links?';
            //return $this->render('error');
        }
    }
}
