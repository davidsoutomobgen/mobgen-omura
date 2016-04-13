<?php
/**
 * Created by PhpStorm.
 * User: sascha
 * Date: 4/5/2016
 * Time: 5:27 PM
 */

namespace console\controllers;

use yii\console\Controller;

class HelloController extends Controller
{
    public $message;

    public function options()
    {
        return ['message'];
    }

    public function optionAliases()
    {
        return ['m' => 'message'];
    }

    public function actionIndex()
    {
        echo $this->message . "\n";
    }
}
