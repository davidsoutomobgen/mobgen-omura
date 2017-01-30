<?php

namespace backend\models;

use Yii;
use common\models\User;
use backend\models\Utils;
use backend\models\BuildsNotification;

/**
 * This is the model class for table "builds".
 *
 * @property integer $buiId
 * @property string $buiName
 * @property string $buiSafename
 * @property string $buiCreated
 * @property string $buiModified
 * @property string $buiTemplate
 * @property string $buiFile
 * @property string $buiVersion
 * @property string $buiBuildNum
 * @property string $buiChangeLog
 * @property integer $buiProIdFK
 * @property integer $buiCerIdFK
 * @property integer $buiType
 * @property string $buiBuildType
 * @property string $buiApple
 * @property string $buiSVN
 * @property string $buiFeedUrl
 * @property integer $buiVisibleClient
 * @property integer $buiDeviceOS
 * @property integer $buiLimitedUDID
 * @property string $buiBundleIdentifier
 * @property string $buiHash
 * @property integer $buiFav
 * @property integer $buiSendEmail
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $createdBy
 * @property OtaProjects $buiProIdFK0
 * @property BuildsDownloaded[] $buildsDownloadeds
 * @property BuildsNotification[] $buildsNotifications
 * @property BuildsQa[] $buildsQas
 */
class Builds extends \common\models\CActiveRecord
{

    public $fld_sent_email;
    public $fld_email_list;
    public $status;
    public $searchString;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'builds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buiName', 'buiSafename', 'buiProIdFK', 'buiVisibleClient', 'buiFav', 'buiSendEmail'], 'required'],
            [['buiCreated', 'buiModified', 'searchString'], 'safe'],
            [['buiChangeLog'], 'string'],
            [['buiProIdFK', 'buiCerIdFK', 'buiType', 'buiVisibleClient', 'buiDeviceOS', 'buiLimitedUDID', 'buiFav', 'buiSendEmail', 'created_by', 'created_at', 'updated_at'], 'integer'],
            [['buiName', 'buiSafename', 'buiTemplate', 'buiFile', 'buiVersion', 'buiApple', 'buiSVN'], 'string', 'max' => 64],
            [['buiBuildNum', 'buiHash'], 'string', 'max' => 16],
            [['buiBuildType'], 'string', 'max' => 255],
            [['buiFeedUrl', 'buiBundleIdentifier'], 'string', 'max' => 128],
            [['buiHash'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['buiProIdFK'], 'exist', 'skipOnError' => true, 'targetClass' => OtaProjects::className(), 'targetAttribute' => ['buiProIdFK' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'buiId' => Yii::t('app', 'Bui ID'),
            'buiName' => Yii::t('app', 'Name'),
            'buiSafename' => Yii::t('app', 'Bui Safename'),
            'buiCreated' => Yii::t('app', 'Bui Created'),
            'buiModified' => Yii::t('app', 'Bui Modified'),
            'buiTemplate' => Yii::t('app', 'Template'),
            'buiFile' => Yii::t('app', 'File'),
            'buiVersion' => Yii::t('app', 'Version'),
            'buiBuildNum' => Yii::t('app', 'Build number'),
            'buiChangeLog' => Yii::t('app', 'Changelog'),
            'buiProIdFK' => Yii::t('app', 'Bui Pro Id Fk'),
            'buiCerIdFK' => Yii::t('app', 'Certificate - iOS only'),
            'buiType' => Yii::t('app', 'OS'),
            'buiBuildType' => Yii::t('app', 'Bui Build Type'),
            'buiApple' => Yii::t('app', 'Apple account'),
            'buiSVN' => Yii::t('app', 'Feed version'),
            'buiFeedUrl' => Yii::t('app', 'Feed URL'),
            'buiVisibleClient' => Yii::t('app', 'Visible to client'),
            'buiDeviceOS' => Yii::t('app', 'Bui Device Os'),
            'buiLimitedUDID' => Yii::t('app', 'Bui Limited Udid'),
            'buiBundleIdentifier' => Yii::t('app', 'Bui Bundle Identifier'),
            'buiHash' => Yii::t('app', 'Bui Hash'),
            'buiFav' => Yii::t('app', 'Fav'),
            'buiSendEmail' => Yii::t('app', 'Sent notification email with install link'),
            'created_by' => Yii::t('app', 'Created by'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuiProIdFK0()
    {
        return $this->hasOne(OtaProjects::className(), ['id' => 'buiProIdFK']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildsDownloadeds()
    {
        return $this->hasMany(BuildsDownloaded::className(), ['buiId' => 'buiId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildsNotifications()
    {
        return $this->hasMany(BuildsNotification::className(), ['buiId' => 'buiId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuildsQas()
    {
        return $this->hasMany(BuildsQa::className(), ['buiId' => 'buiId'])->orderBy(['builds_qa.updated_at'=>SORT_DESC]);
    }

    public function getLastBuildsQas()
    {        
        return $this->hasMany(BuildsQa::className(), ['buiId' => 'buiId'])->orderBy(['builds_qa.updated_at'=>SORT_DESC])->limit(1);        
    }

    /**
     * @inheritdoc
     * @return BuildsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BuildsQuery(get_called_class());
    }


    //Builds
    public static function _GenerateHash($length = 12)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    public static function _GenerateSecureApiHash($projectName, $length = 30)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
        $string = "";

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        $hash = "";

        $hash = crypt($projectName . $string, $string . $string);

        for ($p = $length; $p > 0; $p--) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        $hash .= $string;

        return substr($hash, 0, $length);
    }

    public static function _GenerateSafeFileName($text)
    {
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('/[^-\w]+/', '', $text);
        return $text;
    }

    public static function _GetExtension($filename)
    {
        $parts = pathinfo($filename);
        $extension = $parts['extension'];
        return $extension;
    }

    public static function _RemoveExtension($filename)
    {
        $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
         return $withoutExt;
    }

    public static function _getUDIDs($file) {
    
        $zip = new \ZipArchive;
        if ($zip->open($file) === TRUE) {
            $parts = pathinfo($file);
            require_once(Yii::$app->params["BACKEND_WEB"] . 'cfpropertylist/CFPropertyList.php');
            $data = $zip->getFromIndex($zip->locateName('embedded.mobileprovision', \ZIPARCHIVE::FL_NODIR));
            $zip->close();
            $data = substr($data, strpos($data, '<?xml'));
            $data = substr($data, 0, strpos($data, '</plist>') + 8);
            $plist = new \CFPropertyList();
            $plist->parse($data);
            $plist = $plist->toArray();

            if (isset($plist['ProvisionedDevices'])) {
                $udids = $plist['ProvisionedDevices'];
            }
            else {
                $udids = array();
            }
            //echo '<pre>';print_r($udids);echo'</pre>';die;
            return $udids;
        } else {
            throw new \Exception("IPA could not get opened\n");
        }
    }


    public static function _getIdentifier($filename) {
        $zip = new \ZipArchive;
        if ($zip->open($filename) === TRUE) {
            $parts = pathinfo($filename);
            require_once(Yii::$app->params["BACKEND_WEB"] . 'cfpropertylist/CFPropertyList.php');

            $data = $zip->getFromIndex($zip->locateName('embedded.mobileprovision', \ZIPARCHIVE::FL_NODIR));
            $zip->close();
            $data = substr($data, strpos($data, '<?xml'));
            $data = substr($data, 0, strpos($data, '</plist>') + 8);
            $plist = new \CFPropertyList();
            $plist->parse($data);
            $plist = $plist->toArray();
            $entitlements = $plist['Entitlements'];
            return substr($entitlements['application-identifier'], strpos($entitlements['application-identifier'], '.') + 1);
        } else {
            throw new \Exception("IPA could not get opened\n");
        }
    }


    public static function _getPlist($filename) {
        $zip = new \ZipArchive;
        if ($zip->open($filename) === TRUE) {
            $parts = pathinfo($filename);
            require_once(Yii::$app->params["BACKEND_WEB"] . 'cfpropertylist/CFPropertyList.php');

            $data = $zip->getFromIndex($zip->locateName('embedded.mobileprovision', \ZIPARCHIVE::FL_NODIR));
            $zip->close();
            $data = substr($data, strpos($data, '<?xml'));
            $data = substr($data, 0, strpos($data, '</plist>') + 8);
            $plist = new \CFPropertyList();
            $plist->parse($data);
            $plist = $plist->toArray();
            $entitlements = $plist['Entitlements'];

            //echo '<pre>';print_r($plist);echo'</pre>';//die;
            $info['identifier'] = substr($entitlements['application-identifier'], strpos($entitlements['application-identifier'], '.') + 1);
            $info['appName'] = $plist['AppIDName'];
            if (isset($plist['Entitlements']['aps-environment']))
                $info['aps-environment'] = $plist['Entitlements']['aps-environment'];
            else
                $info['aps-environment'] = '';
            return $info;
        } else {
            throw new \Exception("IPA could not get opened\n");
        }
    }


    public static function _getPackage($filename) {
        $zip = new \ZipArchive;
        if ($zip->open($filename) === TRUE) {
            $parts = pathinfo($filename);

            $data = $zip->getFromIndex($zip->locateName('AndroidManifest.xml', \ZIPARCHIVE::FL_NODIR));
            $zip->close();
//          $test = preg_match("/\x{00}\x{00}\x{00}\x{12}\x{00}(.*)\x{00}\x{00}\x{00}\x{10}\x{00}/U",$data,$matches);
            $test = preg_match("/\x{63}\x{00}\x{6F}\x{00}\x{6D}\x{00}\x{2E}(.*)\x{00}\x{00}/U",$data,$matches);

            return str_replace("\x00","",$matches[0]);
        } else {
            throw new \Exception("APK could not get opened\n");
        }
        return false;
    }

    public static function _GetCurrentDomain() {
        $protocol = 'http';
        if ($_SERVER['SERVER_PORT'] == 443
                || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                || isset($_SERVER["HTTP_HTTPSOFFLOADREQUEST"]) ) {
            $protocol .= 's';
            $protocol_port = $_SERVER['SERVER_PORT'];
        } else {
            $protocol_port = 80;
        }
        $host = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['SERVER_PORT'];
        $request = $_SERVER['PHP_SELF'];
        //      $query = substr($_SERVER['argv'][0], strpos($_SERVER['argv'][0], ';') + 1);
        $toret = $protocol . '://' . $host . ($port == $protocol_port ? '' : ':' . $port) . $request . (empty($query) ? '' : '?' . $query);
        return $toret;
    }

    public static function _SendMail($to, $template, $domain, $project, $build, $userid) {

        //$projpath =  Yii::$app->params["TEMP_BUILD_DIR"]; 

        $buildhash = $build->buiHash;
        $safename = $build->buiSafename;

        $url = $domain ."/build/". $buildhash ."/". $safename;
        /*
        $data = file_get_contents('https://chart.googleapis.com/chart?chs=320x320&cht=qr&chl='. urlencode($url));
        
        if (false !== $data) {
            $qrfile = $projpath ."/html/images/qrcode/$buildhash.jpg";
            //echo "creating QR code: $qrfile<br />\n";
            if ($fh = fopen($qrfile, "w+")) {
                fwrite($fh, $data);
                fclose($fh);
                $qrcode = $domain ."/images/qrcode/". $buildhash .".jpg";
                $smarty->assign("qrcode", $qrcode);
            }
        }
        */        
        $mailtemplate = $template . Utils::getTemplateById($build->buiTemplate) . "/email.html";
        //echo $mailtemplate."\n";die;
        if (!is_file($mailtemplate)) {
            $mailtemplate = $template. "default/email.html";
        }

        $tmpcont = @file_get_contents($mailtemplate);
        if ($tmpcont === false) {
            echo "Error reading content file: $mailtemplate\n";
            return false;
        }

        //var_dump($tmpcont);die;
        if ($build['buiDeviceOS'] == 0) {
            $subject = '[MG-OTA iOS] '. $project->name . " - " . $build->buiBuildType . ' - ' . $build->buiVersion;
            $appType = 'iOS';
        } else {
            $subject = '[MG-OTA Android] '. $project->name . " - " . $build->buiBuildType . ' - ' . $build->buiVersion;
            $appType = 'Android';
        }

        // Set the mail vars we need in the email
        $mail = str_replace('{{$subject}}', $subject, $tmpcont);
        //str_replace('{{app}}', $project->name ." - ". $build->buiBuildType, $mail);          
        $mail = str_replace('{{$appType}}', $appType, $mail);
        $mail = str_replace('{{$build.buiName}}', $build->buiName, $mail);
        $mail = str_replace('{{$build.buiBuildNum}}', $build->buiBuildNum, $mail);
        $mail = str_replace('{{$build.buiBuildType}}', $build->buiBuildType, $mail);
        $mail = str_replace('{{$build.buiVersion}}', $build->buiVersion, $mail);
        $mail = str_replace('{{$build.changelog}}', $build->buiChangeLog, $mail);
        $mail = str_replace('{{$url}}', $url, $mail);
        $mail = str_replace('{{$project.proName}}', $project->name, $mail);
        //str_replace('{{build}}', $build, $tmpcont);
/*
        $mail = Utils::remove_extra_crs($mail);  // Must do this: replaces returns.
        //echo "mail: $mail<br />\n";die;

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= 'To: '. $to . "\r\n"; // Additional headers

//      echo "to: $to<br />\n";
        mail($to, $subject, $mail, $headers);

*/

        $emails = explode(',', $to);

        $sendTo = '';
        foreach ($emails as $email){
            $sendTo [] = trim($email);
        }

        //$sendTo = 'david.souto@mobgen.com';

        $sendEmail = Yii::$app->mailer->compose()
            ->setFrom(['otashare@mobgen.com' => 'OTAShare - MOBGEN'])
            ->setTo($sendTo)
            ->setSubject($subject)
            ->setHtmlBody($mail)
            ->send();

        //print_r($sendEmail);

        $modelNotification = new BuildsNotification();
        $modelNotification->buiId = $build->buiId;
        $modelNotification->email = $to;
        $modelNotification->created_by =  $userid;
        $modelNotification->save();


        return false;
    }

    /*
    public static function getA(){
        //echo 'aki'; die;
        $id = 1035;
        $count = Builds::find()
            ->select(['COUNT(*) AS cnt'])
            ->where('buiProIdFK = '.$id)
            ->one();

        return 35; //$count->cnt;
    }
    */

}
