<?php
namespace frontend\controllers;

use Yii;
use backend\models\Builds;
use backend\models\BuildsDownloaded;
/*use backend\models\BuildsSearch;
use backend\models\OtaProjectsBuildtypes;
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
class BuildController extends Controller
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
    //public function actionIndex()
    public function actionIndex($hash, $safename)
    {
        $model = Builds::find()->where("buiHash = '$hash' AND buiSafename = '$safename' ")->one();
        if (!empty($model)) {
            $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
            //echo $path_file . '  ----  ' . $model->buiVisibleClient . '<br>';
            //CHANGE THIS WHEN TEMPLATES WILL BE ADMiNISTRABLES FROM BACKEND
            switch ($model->buiTemplate) {
                case 0:
                    $build = Yii::$app->params["TEMPLATES"] . 'default/build.php';
                    break;
                case 1:
                    $build =  Yii::$app->params["TEMPLATES"] . 'abnamro/build.php';
                    break;
                case 2:
                    $build = Yii::$app->params["TEMPLATES"] . 'shell-innovation/build.php';
                    break;
                case 3:
                    $build =  Yii::$app->params["TEMPLATES"] . 'redevco/build.php';
                    break;
                case 4:
                    $build = Yii::$app->params["TEMPLATES"] . 'whoiswho_ron/build.php';
                    break;
                case 5:
                    $build = Yii::$app->params["TEMPLATES"] . 'nationalexpress/build.php';
                    break;
            }


            if ($model->buiVisibleClient == 1 && file_exists($path_file)) {
                return $this->renderFile($build, [
                    'buildata' => $model,
                    'url' => $path_file,
                ]);
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
                die;
            }
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
        
        return false;
    }

    public function actionDownload($id) {

        $model = Builds::find()->where(" buiId = $id ")->one();
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
