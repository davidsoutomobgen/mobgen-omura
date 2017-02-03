<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$appLink = Yii::$app->params["FRONTEND"] . '/build/' . $model->buiHash;

$url = Yii::$app->params["FRONTEND"] . '/build/qrcode/' . $model->buiHash;
if ($appType == 'Android')
    $icon = 'android.png';
else
    $icon = 'apple.png';

?>
<style type="text/css">
a{color: #E65B2C}
a:link{text-decoration:none;}
a:hover{text-decoration:underline;}
#background-table{border-collapse:collapse;background-color:#ffffff;}
#logo{padding:0;border-collapse:collapse;vertical-align:top;width:32px;padding-right:8px;}
#logo img{border-radius:3px;vertical-align:top;}
#footer-text div{width:100%;font-size:10px;line-height:14px;font-weight:400;color:#d6dde1;text-align:right;}
</style>
<title></title>
<table id="background-table" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
    <tbody>
    <tr>
        <td id="header_container" style="padding:0;border-collapse:collapse;padding:10px 20px">
            <table id="header-pattern" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                <tbody>
                    <tr>
                        <td id="logo" valign="top" width="88">
                            <img src="https://mobgen.com/files/mailsignature/Mobgen_logo_Accenture_Digital.png" alt="MOGBEN"  width="88" border="0" >
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td id="email-content-container" style="padding:0;border-collapse:collapse;padding:0 20px">
            <table id="email-content-table" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-spacing:0;border-collapse:separate">
                <tbody>
                <tr>
                    <td style="border-collapse:collapse;color:#ffffff;padding:0 15px 0 16px; border-left:1px solid #cccccc;border-top:1px solid #cccccc;border-right:1px solid #cccccc;border-bottom:0;border-top-right-radius:5px;border-top-left-radius:5px;height:10px;line-height:10px" height="10" bgcolor="#ffffff">&nbsp;</td>
                </tr>
                <tr>
                    <td class="email-content-main mobile-expand" style="padding:0;border-collapse:collapse;border-left:1px solid #cccccc;border-right:1px solid #cccccc;border-top:0;border-bottom:0;padding:0 15px 0 16px;background-color:#ffffff" bgcolor="#ffffff">
                        <table class="page-title-pattern" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse">
                            <tbody>
                            <tr>
                                <td class="page-title-pattern-first-line" style="padding:0;border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;padding-top:10px">
                                    <?php
                                    ?>
                                    <span  style="color:#157efb">OTAShare</span> | <span style="color: #157efb;;"><img src="https://otashare-front.mobgen.com/images/<?php echo $icon; ?>" height="16" width="16" border="0" align="absmiddle" alt="Bug" style="vertical-align:text-bottom"> <?php echo $project->name;?></span>
                                </td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial,sans-serif; color:#157efb; vertical-align:top;padding:0;border-collapse:collapse;padding-top:10px; padding-right:5px;font-size:20px;line-height:30px">
                                    <?php echo $model->buiName; ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;border-collapse:collapse;border-left:1px solid #cccccc;border-right:1px solid #cccccc;border-top:0;border-bottom:0;padding:0 15px 0 16px;background-color:#ffffff;padding-top:10px;padding-bottom:5px" bgcolor="#ffffff">
                        <table style="border-collapse:collapse">
                            <tbody>
                                <tr>
                                    <th style="color:#707070; font:normal 14px/20px Arial,sans-serif;text-align:left;vertical-align:top;padding:2px 0">
                                        Number:
                                    </th>
                                    <td style="color:#3b73af; padding:0;border-collapse:collapse;font:normal 14px/20px Arial,sans-serif;padding:2px 0 2px 5px;vertical-align:top">
                                        <?php echo $model->buiBuildNum; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="color:#707070;font:normal 14px/20px Arial,sans-serif;text-align:left;vertical-align:top;padding:2px 0">Version: </th>
                                    <td style="padding:0;border-collapse:collapse;font:normal 14px/20px Arial,sans-serif;padding:2px 0 2px 5px;vertical-align:top"><?php echo $model->buiVersion; ?></td>
                                </tr>
                                <tr>
                                    <th style="color:#707070;font:normal 14px/20px Arial,sans-serif;text-align:left;vertical-align:top;padding:2px 0">Type:</th>
                                    <td style="padding:0;border-collapse:collapse;font:normal 14px/20px Arial,sans-serif;padding:2px 0 2px 5px;vertical-align:top"><?php echo $model->buiBuildType; ?></td>
                                </tr>
                                <tr>
                                    <th style="color:#707070;font:normal 14px/20px Arial,sans-serif;text-align:left;vertical-align:top;padding:2px 0">ChangeLog:</th>
                                    <td style="padding:0;border-collapse:collapse;font:normal 14px/20px Arial,sans-serif;padding:2px 0 2px 5px;vertical-align:top"><?php echo $model->buiChangeLog; ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src='https://otashare-front.mobgen.com/build/qrcode/<?php echo $model->buiHash;?>' />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td class="email-content-main mobile-expand" style="padding:0;border-collapse:collapse;border-left:1px solid #cccccc;border-right:1px solid #cccccc;border-top:0;border-bottom:0;padding:0 15px 0 16px;background-color:#ffffff" bgcolor="#ffffff">
                        <table id="actions-pattern" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;line-height:20px">
                            <tbody>
                            <tr>
                                <td id="actions-pattern-container" valign="middle" style="padding:0;border-collapse:collapse;padding:10px 0 10px 24px;vertical-align:middle;padding-left:0">
                                    <table align="left" style="border-collapse:collapse">
                                        <tbody>
                                        <tr>
                                            <td class="actions-pattern-action-icon-container" style="padding:0;border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;line-height:20px;vertical-align:middle">&nbsp;</td>
                                            <td class="actions-pattern-action-text-container" style="padding:0;border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;line-height:20px;padding-left:5px">
                                                <a href="<?php echo $appLink; ?>" title="Link to the app" style="color:#157efb;text-decoration:none" target="_blank">
                                                    <img src="https://otashare-front.mobgen.com/images/download.png" height="20" width="20" border="0" align="absmiddle" alt="Bug" style="vertical-align:middle"> Link to the app
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="email-content-rounded-bottom mobile-expand" style="padding:0;border-collapse:collapse;color:#ffffff;padding:0 15px 0 16px;height:5px;line-height:5px;background-color:#ffffff;border-top:0;border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;border-right:1px solid #cccccc;border-bottom-right-radius:5px;border-bottom-left-radius:5px" height="5" bgcolor="#ffffff">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td id="footer-pattern" style="padding:0;border-collapse:collapse;padding:12px 20px">
            <table  width="100%" id="footer-pattern-content" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse">
                <tbody>
                    <tr>
                        <td id="footer-text" width="100%" style="padding:0; border-collapse:collapse; font-size:12px;line-height:18px; text-align:right">
                            <div style="color:#808080;">
                                © <?php echo date('Y'); ?> MOBGEN.<br>
                                Please do not reply to this email.
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
