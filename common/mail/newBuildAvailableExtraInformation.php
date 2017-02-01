<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$appLink = Yii::$app->params["FRONTEND"] . '/build/' . $model->buiHash;

$url = Yii::$app->params["FRONTEND"] . '/build/qrcode/' . $model->buiHash;

if ($model['buiDeviceOS'] == 0) {
    $appType = 'iOS';
} else {
    $appType = 'Android';
}

?>
<style type="text/css">
a{color: #9c34a5}
a:link{text-decoration:none;}
a:hover {text-decoration:underline;}
font[size=\'3\']{font-size: 14px;}
font[size=\'2\']{font-size: 12px;}
font[size=\'1\']{font-size: 10px;}
br{font-size:18px;}
#contenido,#contenido font{font-family: Helvetica, Arial, sans-serif !important;font-size: 13px !important;}
#contenido{color: #333}
#pie a{color:#9c34a5}
a img{border:0;}
ul li{color: #0071bc;font-size:10px;}
hr{border:0; border-bottom:1px solid #b0b0b0}
#contenido hr{border:0; border-bottom:2px dashed #b0b0b0; margin: 35px 0}
.legal, tr .legal a, .copy{color:#4d4d4d;font-size:9px !important;line-height:11px;text-align:justify}
.copy {font-size:10px !important}
.button {box-shadow: 3px 3px 3px rgba(0,0,0,.7); border-radius: 10px; line-height: 120%} #contenido .button font[size=\'3\']{font-size: 16px !important;}
</style>
<title></title>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tbody>
    <tr>
      <td bgcolor="#F0F0F0">
        <center>
          <table cellspacing="0" cellpadding="0" border="0" width="600"><tbody>
            <tr><td><center>
              <table cellspacing="0" cellpadding="0" border="0" width="530"><tbody>
                <tr>
                <td width="310">
                    <br><br>
                        <img width="150px" src="https://mobgen.com/files/mailsignature/Mobgen_logo_Accenture_Digital.png" alt="MOGBEN">
                    <br><br>
                </td>
                <td width="23"></td>
                <td></td>
                </tr>
              </tbody></table>
            </center></td></tr>
            <tr height="10"><td></td></tr>
            <tr><td id="contenido" bgcolor="#FFFFFF"><center>
              <table cellspacing="0" cellpadding="0" border="0" width="530"><tbody>
                <tr height="35"><td></td></tr>
                <tr><td>
                    <p>Hello,</p>
                    <p>You have available a new app version of the project <?= $project->name;?>:</p><br>
                    <h3>App Information</h3>
                    <p><b>APP Type</b>: <?php echo $appType; ?></p>
                    <p><b>Number</b>: <?php echo $model->buiName; ?></p>
                    <p><b>Number</b>: <?php echo $model->buiBuildNum; ?></p>
                    <p><b>Type</b>: <?php echo $model->buiBuildType; ?></p>
                    <p><b>Version</b>: <?php echo $model->buiVersion; ?></p>
                    <p><b>Changelog</b>: <?php echo $model->buiChangeLog; ?></p><br>

                    <?php /* echo $url; */ ?>
                    <!--<img src='https://otashare-front.mobgen.com/build/qrcode/jeqc6qonrgt9' />-->

                    <p><?= Html::a(Html::encode('Link to the app'), $appLink) ?></p><br><br>
                    <p>Regards,</p>
                    <p>MOBGEN</p>
                </td></tr>
                <tr height="35"><td></td></tr>
              </tbody></table>
            </center></td></tr>
            <tr><td id="pie"><center>
              <table cellspacing="0" cellpadding="0" border="0" width="530"><tbody><tr height="10"><td colspan="3"></td></tr></tbody></table>
              <table cellspacing="0" cellpadding="0" border="0" width="530"><tbody>
                <tr><td colspan="2"><hr></td></tr>
                <tr>
                  <td class="copy" width="75%"><font class="copy" size="1" color="#E65B2C" face="Helvetica, Arial, sans-serif"><?php echo date('Y'); ?> © MOBGEN Copyright. All rights reserved.</font></td>
                  <td class="copy"></td>
                </tr>
                <tr><td colspan="2"><hr></td></tr>
                <tr><td class="enlaces" colspan="2"></td></tr>
              </tbody></table><br><br>
            </center></td></tr>
          </tbody></table>
        </center>
      </td>
    </tr>
  </tbody>
</table>
