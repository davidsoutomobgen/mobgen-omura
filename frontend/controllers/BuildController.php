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
    public function actionIndex($hash, $safename = '')
    {

        if (!empty ($safename))
            $model = Builds::find()->where("buiHash = '$hash' AND buiSafename = '$safename' ")->one();
        else
            $model = Builds::find()->where("buiHash = '$hash'")->one();

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
                case 6:
                    $build = Yii::$app->params["TEMPLATES"] . 'james-beta-user/build.php';
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

    public function actionIpa ($hash, $safename, $plist) {

        $model = Builds::find()->where("buiHash = '$hash' AND buiSafename = '$safename' ")->one();
        $path_file = Yii::$app->params["FRONTEND"] . '/build/download/' . $model->buiHash;
        //http://omura-david-front.mobgendev105.com/build/download/13245';

        $filename2 = $model->buiId . ".ipa";
        $path_file2 = Yii::$app->params["BUILD_DIR"] . $filename2;
        $info = Builds::_getPlist($path_file2);
        //echo '<pre>'; print_r($info); echo '</pre>';

        $plist = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><!DOCTYPE plist PUBLIC "-//Apple Computer//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd"><plist version="1.0"/>');
        $dict = $plist->addChild('dict');
        $dict->addChild('key', 'items');
        $array = $dict->addChild('array');
            $dict2 = $array->addChild('dict');
                
                $dict2->addChild('key', 'assets');
                $array2 = $dict2->addChild('array');
                    $dict3 = $array2->addChild('dict');
                    $dict3->addChild('key', 'kind');
                    $dict3->addChild('string', 'software-package');
                    $dict3->addChild('key', 'url');
                    $dict3->addChild('string', $path_file);
                    
                    $dict3 = $array2->addChild('dict');
                    $dict3->addChild('key', 'kind');
                    $dict3->addChild('string', 'display-image');
                    $dict3->addChild('key', 'needs-shine');
                    $dict3->addChild('true');
                    $dict3->addChild('key', 'url');
                    $dict3->addChild('string', 'https://otashare-front.mobgen.com/images/ios_57x57.png');
                    
                    $dict3 = $array2->addChild('dict');
                    $dict3->addChild('key', 'kind');
                    $dict3->addChild('string', 'full-size-image');
                    $dict3->addChild('key', 'needs-shine');
                    $dict3->addChild('true');
                    $dict3->addChild('key', 'url');
                    $dict3->addChild('string', 'https://otashare-front.mobgen.com/images/ios_512x512.jpg');

                $dict2->addChild('key', 'metadata');

                $dict4 = $dict2->addChild('dict');
                $dict4->addChild('key', 'bundle-identifier');
                $dict4->addChild('string', $info['identifier']);
                $dict4->addChild('key', 'bundle-version');
                $dict4->addChild('string', $info['aps-environment']);
                $dict4->addChild('key', 'kind');
                $dict4->addChild('string', 'software');
                $dict4->addChild('key', 'title');
                //Choose $model->buiSafename or $info['appName']
                $dict4->addChild('string', $info['appName']);

        Header('Content-type: text/xml');
        print($plist->asXML());
        die;
    }

    public function actionDownload($hash) {

        $model = Builds::find()->where(" buiHash = '$hash' ")->one();

        if (!empty($model)) {
            $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
            if ($model->buiVisibleClient == 1 && file_exists($path_file)) {

                $extension = Builds::_GetExtension($model->buiFile);
                $filename = $model->buiSafename.'.'.$extension;
                
                $downloaded = new BuildsDownloaded();
                $downloaded->buiId = $model->buiId;
                $downloaded->save();
                
                return  Yii::$app->response->sendFile($path_file, $filename);               
            }
            else {
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

    public function actionDownload_hash($hash) {

        $model = Builds::find()->where(" buiHash = $hash ")->one();

        if (!empty($model)) {
            $path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
            if ($model->buiVisibleClient == 1 && file_exists($path_file)) {

                $extension = Builds::_GetExtension($model->buiFile);
                $filename = $model->buiSafename.'.'.$extension;

                $downloaded = new BuildsDownloaded();
                $downloaded->buiId = $model->buiId;
                $downloaded->save();

                return  Yii::$app->response->sendFile($path_file, $filename);
            }
            else {
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
