<?php
namespace frontend\controllers;

use Yii;
use backend\models\OtaProjects;
use backend\models\Builds;
use backend\models\BuildsDownloaded;

/*
 * use backend\models\OtaProjectsBuildtypes;
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
        $model = OtaProjects::find()->where("proHash = '$hash' AND safename = '$safename' ")->one();
        //echo 'dddddd<pre>'; print_r($model); echo '</pre>'; die;
        if (!empty($model)) {
            //$path_file = Yii::$app->params["DOWNLOAD_BUILD_DIR"] .  $model->buiFile;
            $builds = Builds::find()->where("buiProIdFK = '$model->id' AND buiVisibleClient = 1")->all();

            $project = '/data/www/mobgen-moby/frontend/web/templates/default/project_new.php';


            //if ($model->buiVisibleClient == 1 && file_exists($path_file)) {
            if (1) {
                return $this->renderFile($project, [
                    'project' => $model,
                    'builds' => $builds,
                    //'url' => $path_file,
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
        die;
        /////////


        if ($buidata = $csql->FetchArray($tmpres)) {
            if ($buidata['buiVisibleClient'] == 1 && file_exists($buidata['buiFile'])) {
                include_once('mod_admin.php');

                $projectUrl = '/project/'.$buidata['proHash'].'/'.$buidata['proSafeName'];
                $cfc->page_set_vars["projectUrl"] = $projectUrl;

                $extension = $buidata['buiDeviceOS'] == 0 ? "ipa" : "apk";
                if (count($parts) >= 3 && $parts[2] == "download") {
                    if (file_exists($buidata['buiFile'])) {
                        // Set response headers
                        $filesize = filesize($buidata['buiFile']);
                        if ($extension == "apk") {
                            header('Accept-Ranges: bytes');
                            header('Content-Type: application/octet-stream');
                            header("Content-Disposition: attachment; filename=\"".$buidata['buiSafename'].".".$extension."\"");
//							header("Content-Disposition: attachment; filename=\"app-test.".$extension."\"");
//							header("Content-Transfer-Encoding: binary");
                            header('Content-Length: '. $filesize);
                        } else {
                            header('Accept-Ranges: bytes');
                            header('Content-Type: application/octet-stream');
                            header("Content-Disposition:attachment;filename=".$buidata['buiSafename'].".".$extension);
                            header("Content-Transfer-Encoding: binary");
                            header('Content-Length: '. $filesize);
                        }
                        if ($handle = fopen($buidata['buiFile'], "r")) {
                            // output buffered, so we can handle large files
                            while (!feof($handle)) {
                                echo fread($handle, 8192);
                            }
                            fclose($handle);
                        } else {
                            echo "failed to open content!<br />\n";
                        }
                    } else {
                        echo "Sorry but this build doesn't exist (anymore.)";
                        return false;
                    }

                } elseif (count($parts) == 3 && $parts[2] == "plist") {
                    header('Content-Type: text/xml');
                    $cfc->page_set_vars["url"] = ModAdminClass::_GetCurrentDomain()."/build/". $buidata['buiHash'] ."/". $buidata['buiSafename'] ."/download";
                    $cfc->page_set_vars["build"] = array(
                        "buiBundleIdentifier"	=> $buidata['buiBundleIdentifier'],
                        "buiSafename"			=> $buidata['buiSafename'],
                    );
                    $cfc->ChainModAction("default", "page", "plist.html");
                    return true;

                } else {
                    if ($buidata['buiDeviceOS'] == 0) {
                        $cfc->page_set_vars["url"] = "itms-services://?action=download-manifest&url=". ModAdminClass::_GetCurrentDomain() ."/build/". $buidata['buiHash'] ."/". $buidata['buiSafename'] ."/plist";
                    } else {
                        // @SAS: testing for downloading bug
//						$cfc->page_set_vars["url"] = "/build/". $buidata['buiHash'] ."/". $buidata['buiSafename'] ."/download";
                        $cfc->page_set_vars["url"] = "/build/". $buidata['buiHash'] ."/". $buidata['buiSafename'] ."/download/{$buidata['buiSafename']}.$extension";
                    }

                    $cfc->page_set_vars["builddata"] = $buidata;

                    // Set template
                    if (file_exists("web/templates/".$cfc->cconfig->env_actsetup['TEMPLATES_DIRS'][$buidata['buiTemplate']]."/build.html")) {
                        $cfc->ChainModAction("default", "page", "templates/".$cfc->cconfig->env_actsetup['TEMPLATES_DIRS'][$buidata['buiTemplate']]."/build.html");
                    } else {
                        $cfc->ChainModAction("default", "page", "templates/default/build.html");
                    }
                    return true;
                }
            } else {
                echo "Sorry but this build doesn't exist (anymore.) If you think this is an error, please contact us.";
                return false;
            }
        }
        return false;

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
