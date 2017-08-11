<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\helpers\Url;

class CController extends Controller
{
     public function redirect($url, $statusCode = 302)
     {
         if ($url == '/site/logout') {
             $backUrl = Url::current();
             $url = "{$url}?back={$backUrl}";
             //print_r($url); die;
         }
         //print_r($_SERVER);die;
         return parent::redirect($url, $statusCode);
     }
}
