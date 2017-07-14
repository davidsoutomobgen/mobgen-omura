<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\Utils;


class UsageController extends Controller
{

    public function actionIndex()
    {
        $alertLimit = Yii::$app->params['alertLimit'];
        $alertEmails = Yii::$app->params['alertEmails'];

        $hd = Utils::freeSpace();
        $diskUsed = (float) $hd['diskused'];

        $html = "This is an alert because the OTAShare disk is {$hd['diskused']}%";

        if ($diskUsed >= $alertLimit) {
            $sendEmail = Yii::$app->mailer->compose()
                ->setFrom(['otashare@mobgen.com' => 'OTAShare - MOBGEN'])
                ->setTo($alertEmails)
                ->setSubject("🚨 OTAShare - Disk usage alert > {$alertLimit}%")
                ->setHtmlBody($html)
                ->send();

            return true;
        }

        return false;
    }
}
