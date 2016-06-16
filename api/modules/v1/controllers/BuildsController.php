<?php

namespace api\modules\v1\controllers;

use Yii;
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

         //echo $apikey2.' '.$apikeybuild;die;
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
                        //echo '<pre>';print_r($temp);echo'</pre>'; die;
        
                        if (!empty($project)  ) {
                            $build = new Builds();
                            $build->buiProIdFK = $projectid;                        
                            //print_r($post); //die;
                            $post = new Builds();
                            $post->load($temp);

                            //echo '<pre>';print_r($post);echo'</pre>';die;
                            if (isset($post->buiFeedUrl1)) $post->buiFeedUrl1 = urldecode($post['buiFeedUrl1']);

                            if (!empty($post->buiName)) {
                                if ($_FILES['buiFile']['error'] === UPLOAD_ERR_OK) {
                                    $extension = strtolower(Builds::_GetExtension($_FILES['buiFile']['name']));
                                    if ($extension == "ipa") {
                                        $device_os = 0; // iOS
                                        $cerIdFK = 1;
                                    } elseif ($extension == "apk") {
                                        $cerIdFK = 0;
                                    }
                                    // Check extension
                                        $device_os = 1; // Android
                                    if (isset($device_os)) {
                                        //print_r($post->attributes);die;
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

                                    if(isset($post->buiHash) && !empty($post->buiHash)) {
                                        // Add to database
                                        $build = Builds::find()->where('buiHash = :api_build_hash',  [':api_build_hash' =>  $post->buiHash])->one();

                                        if (!isset($build)) {
                                            $error = "Build Hash not exists.\n";
                                            echo $error;
                                            die;
                                        } else {
                                            $build->load($temp);
                                            $build->buiProIdFK = $projectid;                                       
                                            $build->buiSafename = $safe;
                                            $build->buiDeviceOS = $device_os;
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

                                            $project = OtaProjects::find()->where('id = :buiProIdFK',  [':buiProIdFK' =>  $build->buiProIdFK])->one();
                                            $project->updated_at = $build->updated_at;
                                            $project->save();
                                        }
                                    }
                                    else{

                                        $build = Builds::find()->where('buiSafename = :buiSafename',  [':buiSafename' =>  $safe])->one();

                                        if (isset($build)) {
                                            //echo "Build already exists.\n";
                                            //$id = $build->buiId;
                                            //die;
                                            $safe = $safe.'_'.rand();
                                        } 
                                        //else {

                                            $build = new Builds();
                                            $build->load($temp);
                                            $build->buiProIdFK = $projectid;                                       
                                            $build->buiSafename = $safe;
                                            $build->buiDeviceOS = $device_os;
                                            $build->buiCerIdFK = $cerIdFK;
                                            $build->buiSendEmail = $buiSendEmail;
                                            $build->buiVisibleClient = $buiVisibleClient;
                                            $build->buiFav = $buiFav;
                                            $build->buiHash = Builds::_GenerateHash();                                           


                                            $user = User::findByUsername($model->username);  
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

					    $project = OtaProjects::find()->where('id = :buiProIdFK',  [':buiProIdFK' =>  $build->buiProIdFK])->one();
                                            $project->updated_at = $build->updated_at;
					    $project->save();
                                        //}
                                    }
                                
                                    //UPLOAD    
                                    $filename = $id . "." . $extension;
                                    $path_file = Yii::$app->params["BUILD_DIR"] . $filename;

                                    if(isset($id) && move_uploaded_file($_FILES['buiFile']['tmp_name'], $path_file)) {
                                        $build->buiLimitedUDID = 0;
                                        $identifier = "";
                                        if ($extension == "ipa") {
                                                      
                                            $build->buiType = 0; // iOS
                                            $build->buiCerIdFK = 1;

                                            $udids = Builds::_getUDIDs($path_file);
                                            if (count($udids) > 0) {
                                                $bui->buiLimitedUDID = 1;
                                                /*
                                                $udid_array = array();
                                                foreach ($udids as $udid) {
                                                        $udid_array[] .= "('". $csql->RealEscapeNotQuoted($udid) ."','".$id."')";
                                                }
                                                //david.souto - Added to controller the error with the test devices
                                                $filter_array = array_unique($udid_array);
                                                $diff_array = array_diff_assoc($udid_array, $filter_array);
                                                if (!empty($diff_array)) {
                                                    echo "Execution stopped - Error with the ID of the devices. These devices are repeated: \n";
                                                    print_r($diff_array);
                                                    die;
                                                }
                                                
                                                //We don'
                                                //$sqlstr = "INSERT INTO builds_udids (budUdid,budBuiIdFK)
                                                //           VALUES
                                                //           ". implode(",",$udid_array) ."";
                                                //           $csql->Query($sqlstr);
                                                //
                                                */
                                            }
                                            
                                            $identifier = Builds::_getIdentifier($path_file);

                                        } elseif ($extension == "apk") {
                                            $identifier = Builds::_getPackage($path_file);
                                            $build->buiId = $id;
                                            $build->buiType = 1; // Android
                                            $build->buiCerIdFK = 0;
                                        }

                                        $build->buiFile = $filename;
                                        $build->buiBundleIdentifier = $identifier;

                                        if ($build->save()) {
                                            $id = $build->buiId;
                                            $project->save();    

                                            echo "Build ADDED.\n"; 

                                            if ($buiSendEmail) {

                                                if (!empty($temp['Builds']['fld_email_list'])) {       
                                                    $to = $temp['Builds']['fld_email_list'];
                                                }
                                                else {
                                                    $to = $project->default_notify_email;        
                                                }
                                                //$domain = Builds::_GetCurrentDomain();
                                                $domain =  Yii::$app->params["FRONTEND"]; 
                                                $template = Yii::$app->params["TEMPLATES"]; 
                                      
                                                $user = User::findByUsername($model->username);  
                                                Builds::_SendMail($to, $template, $domain, $project, $build, $user->id);
                                                echo "Emails send to: $to \n";
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
                                else echo "Error FILES['error'] = ".$_FILES['buiFile']['error'];
                            }
                            else echo "Error: buiName is empty";
                        }
                        else
                        {   
                            echo "API key or project or API key build ID wrong.";
                        }
                    }
                    else{
                        if ($login)
                            echo "buiUser and buiPassword wrongs.\n";
                        else 
                            echo "No buiUser and/or buiPassword specified.\n";
                        die;
                    }
                }
                else 
                {
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


