<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use backend\models\Builds;
use backend\models\OtaProjects;
use backend\models\OtaProjectsBuildtypes;
use common\models\User;
use common\models\LoginForm;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;



/**
 * Builds Controller API
 *
 * @author David Souto <david.souto@mobgen.com>
 */
class BuildsController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Builds';
    public $prepareDataProvider;

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => 'yii\filters\ContentNegotiator',
                //'only' => ['view', 'index'],  // in a controller
                // if in a module, use the following IDs for user actions
                // 'only' => ['user/view', 'user/index']
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ]);
    }

    /**
     * Last Builds by ota_project->APIKey.
     * @return ActiveDataProvider
     */
    public function actionLastbuilds($apikey){

        $modelClass = $this->modelClass;
        $model = OtaProjects::find()->where('proAPIKey = :apikey',  [':apikey' =>  $apikey])->one();
        //echo '<pre>';print_r($model->id);echo'</pre>';die;

        //$query = $modelClass::find();
        $query = $modelClass::find()
                            ->select('buiBundleIdentifier, buiId, buiName, buiSafename, updated_at as buiModified, buiFile, buiType, buiHash, buiApple, buiDeviceOS, buiVersion, buiProIdFK')
                            ->where('buiProIdFK = :id AND buiVisibleClient = 1',  [':id' =>  $model->id])
                            ->limit(5)
                            ->orderBy('updated_at');
        //echo '<pre>';print_r($query);echo'</pre>';die;

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionTest(){
        $items = ['one', 'two', 'three' => ['a', 'b', 'c']];
        return $items;
    }

    /**
     * Last Builds by ota_project->APIKey.
     * @return ActiveDataProvider
     */
    public function actionRegisternewbuild($projectid = 0, $apikey2 = '', $apikeybuild = ''){

        //Start             
        if(isset($projectid))
        {
            if(isset($apikey2))
            {
                if(isset($apikeybuild))
                {

                    $temp['Builds'] = Yii::$app->request->post();
                    $model = new LoginForm();

                    $login = false;
                    if ((isset($temp['Builds']['buiUser'])) && (isset($temp['Builds']['buiPassword']))) {
                        $model->username = $temp['Builds']['buiUser'];
                        $model->password = $temp['Builds']['buiPassword'];
                        $login = true;                            
                    }                    

                    if ($login && $model->login()) {
                        echo "Login Correct \n";
                                       
                        $project = OtaProjects::find()->where('id = :projectid AND proAPIBuildKey = :apikeybuild AND proAPIKey = :apikey',  [':projectid' =>  $projectid, ':apikeybuild' =>  $apikeybuild, ':apikey' =>  $apikey2])->one();

                        $build_types = OtaProjectsBuildtypes::find()->with('idOtaBuildtypes')->where('id_ota_project = :projectid', [':projectid' => $projectid])->all();

                        $btypes = array();
                        foreach ($build_types as $types) {
                            $btypes[$types->id] = $types->idOtaBuildtypes->name;
                        }

                        if (!empty($project)  ) {
                            $build = new Builds();
                            $build->buiProIdFK = $projectid;                        
                            $post = new Builds();
                            $post->load($temp);

                            if (isset($temp['Builds']['buiHash']))
                                $post->api_build_hash = $temp['Builds']['buiHash'];
                            else if (isset( $temp['Builds']['api_build_hash']))
                                $post->api_build_hash = $temp['Builds']['api_build_hash'];
                            if (isset( $temp['Builds']['fld_email_list']))
                                $post->fld_email_list = $temp['Builds']['fld_email_list'];
                            if (isset( $temp['Builds']['fld_sent_email']))
                                $post->fld_sent_email = $temp['Builds']['fld_sent_email'];

                            if (isset($post->buiFeedUrl1)) $post->buiFeedUrl1 = urldecode($post['buiFeedUrl1']);

                            if (!empty($post->buiName)) {
                                if ($_FILES['buiFile']['error'] === UPLOAD_ERR_OK) {
                                    $extension = strtolower(Builds::_GetExtension($_FILES['buiFile']['name']));
                                    if ($extension == "ipa") {
                                        $device_os = 0; // iOS
                                        $cerIdFK = 1;
                                    } elseif ($extension == "apk") {
                                        $device_os = 1; // Android
                                        $cerIdFK = 0;
                                    }
                                    // Check extension
                                    if (isset($device_os)) {
                                        $safe = Builds::_GenerateSafeFileName($_REQUEST['buiName']);
                                        $buiVisibleClient = (isset($post->buiVisibleClient)) ? '1' : '0';
                                        $buiFav = (isset($post->buiFav)) ? '1' : '0';
                                        $buiSendEmail = (isset($temp['Builds']['fld_sent_email'])) ? '1' : '0';

                                        if (isset($post['buiFeedUrl2'])) {
                                            $buiFeedUrl = $post->buiFeedUrl1 .  $post->buiFeedUrl2 .  $post->buiFeedUrl3 .  $post->buiFeedUrl4;
                                        } else {
                                            $buiFeedUrl = "";
                                        }
                                    } else {
                                        echo "File extension not recognized as valid extension.\n";
                                        die;
                                    }
                                    $user = User::findByUsername($model->username);

                                    if(isset($post->api_build_hash)) {
                                        // Add to database
                                        $build = Builds::find()->where('buiHash = :api_build_hash',  [':api_build_hash' =>  $post->api_build_hash])->one();

                                        if (!isset($build)) {
                                            $error = "Build Hash not exists.\n";
                                            die;
                                        } else {
                                            $build->load($temp);
                                            $build->buiProIdFK = $projectid;                                       
                                            $build->buiSafename = $safe;
                                            $build->buiDeviceOS = $device_os;
                                            $build->buiType = $device_os;
                                            $build->buiCerIdFK = $cerIdFK;
                                            $build->buiSendEmail = $buiSendEmail;
                                            $build->buiVisibleClient = $buiVisibleClient;
                                            $build->buiFav = $buiFav;

                                            $build->updated_at = strtotime(date("Y-m-d H:i:s"));

                                            if ($build->save()) {
                                                $id = $build->buiId;

                                            } else {
                                                echo "Error updating build\n";
                                                die;
                                            }
                                            echo "Build UPDATED Correctly!\n";

                                            $project = new OtaProjects();
                                            $project->load($build->buiProIdFK);
                                            $project->updated_at = $build->updated_at;
                                            $project->save();
                                        }
                                    } else {
                                        $build = new Builds();
                                        $build->load($temp);
                                        $build->buiProIdFK = $projectid;
                                        $build->buiSafename = $safe;
                                        $build->buiDeviceOS = $device_os;
                                        $build->buiType = $device_os;
                                        $build->buiCerIdFK = $cerIdFK;
                                        $build->buiSendEmail = $buiSendEmail;
                                        $build->buiVisibleClient = $buiVisibleClient;
                                        $build->buiFav = $buiFav;
                                        $build->buiHash = Builds::_GenerateHash();

                                        $build->created_by = $user->id;
                                        $build->created_at = strtotime(date("Y-m-d H:i:s"));
                                        $build->updated_at = strtotime(date("Y-m-d H:i:s"));

                                        if ($build->save()) {
                                            $id = $build->buiId;
                                        } else {
                                            print_r($build->getErrors());
                                            echo "Error adding build\n";
                                            die;
                                        }
                                        echo "Build CREATED correctly!\n";

                                        $project = new OtaProjects();
                                        $project->load($build->buiProIdFK);
                                        $project->updated_at = $build->updated_at;
                                        $project->save();
                                    }
                                
                                    //UPLOAD    
                                    $filename = $id . "." . $extension;
                                    $path_file = Yii::$app->params["BUILD_DIR"] . $filename;

                                    if(isset($id) && move_uploaded_file($_FILES['buiFile']['tmp_name'], $path_file)) {
                                        $build->buiLimitedUDID = 0;
                                        $identifier = "";
                                        if ($extension == "ipa") {
                                            
                                            $udids = Builds::_getUDIDs($path_file);
                                            if (count($udids) > 0)
                                                $build->buiLimitedUDID = 1;
                                            
                                            $identifier = Builds::_getIdentifier($path_file);

                                        } elseif ($extension == "apk") {
                                                $identifier = Builds::_getPackage($path_file);
                                                $build->buiId = $id;
                                        }

                                        $build->buiFile = $filename;
                                        $build->buiBundleIdentifier = $identifier;

                                        if ($build->save()) {
                                            $id = $build->buiId;
                                            $project->save();    

                                            echo "Build ADDED.\n";

                                            if ($buiSendEmail) {
                                                if (($post->fld_email_list))
                                                    $to = $post->fld_email_list;
                                                else
                                                    $to = $project->default_notify_email;

                                                //$domain = Builds::_GetCurrentDomain();
                                                $domain =  Yii::$app->params["FRONTEND"]; 
                                                $template = Yii::$app->params["TEMPLATES"]; 
                                                if (!empty($to)) {
                                                    Builds::_SendMail($to, $template, $domain, $project, $build, $user->id);
                                                    echo "Emails send to: $to \n";
                                                }
                                                else
                                                    echo "Error to send Emails. Please notify the admin. \n";
                                            }

                                            $domain =  Yii::$app->params["FRONTEND"]; 

                                            $buildhash = $build->buiHash;
                                            $safename = $build->buiSafename;

                                            $url = $domain ."/build/". $buildhash ."/". $safename; 

                                            echo "$url\n";

                                        } else {
                                            print_r($build->getErrors());
                                            echo "Error adding build\n";
                                            die;
                                        }

                                    } else {
                                        // Delete from database
                                        $model = Builds::findModel($id);
                                        $model->delete();
                                        echo "Error uploading file.\n";
                                    }
                                }
                                else {
                                    echo "Error uploading file. Type error: " . $_FILES['buiFile']['error'] . "\n";
                                }
                            }
                            else
                                echo "No buiName specified.";
                        }
                        else {
                            echo "API key or project or API key build ID wrong.";
                        }
                    }
                    else {
                        if ($login)
                            echo "buiUser and buiPassword wrongs.\n";
                        else 
                            echo "No buiUser and/or buiPassword specified.\n";
                        die;
                    }
                }
                else {
                    echo "No build API key specified.";
                }
            }
            else 
            {
                echo "No API key specified.";
            }
        }
        else
        {
            echo "No project ID specified.";
        }                
        //End          
    }
}


