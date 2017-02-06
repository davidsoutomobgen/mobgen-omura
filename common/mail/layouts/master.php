<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
    a{color: #E65B2C}
    a:link{text-decoration:none;}
    a:hover{text-decoration:underline;}
    #background-table{border-collapse:collapse;background-color:#ffffff;}
    #logo{padding:0;border-collapse:collapse;vertical-align:top;width:32px;padding-right:8px;}
    #logo img{border-radius:3px;vertical-align:top;}
    #footer-text div{width:100%;font-size:10px;line-height:14px;font-weight:400;color:#d6dde1;text-align:right;}
    </style>
</head>
<body>
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
                        <td style="border-collapse:collapse;color:#ffffff;padding:0 15px 0 16px; border-left:1px solid #cccccc;border-top:1px solid #cccccc;border-right:1px solid #cccccc;border-bottom:0;border-top-right-radius:5px;border-top-left-radius:5px;height:10px;line-height:10px" height="10" bgcolor="#ffffff"></td>
                    </tr>
                    <tr>
                        <td style="padding:0;border-collapse:collapse;border-left:1px solid #cccccc;border-right:1px solid #cccccc;border-top:0;border-bottom:0;padding:0 15px 0 16px;background-color:#ffffff;padding-top:10px;padding-bottom:10px" bgcolor="#ffffff">
                            <table style="border-collapse:collapse">
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $content ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="email-content-rounded-bottom mobile-expand" style="padding:0;border-collapse:collapse;color:#ffffff;padding:0 15px 0 16px;height:10px;line-height:10px;background-color:#ffffff;border-top:0;border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;border-right:1px solid #cccccc;border-bottom-right-radius:5px;border-bottom-left-radius:5px" height="10" bgcolor="#ffffff">&nbsp;</td>
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
</body>
</html>
<?php $this->endPage() ?>
