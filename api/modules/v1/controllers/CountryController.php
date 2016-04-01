<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class CountryController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Country';

    //With this behavior only show json format
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



    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }


    public function actionTest(){
        $items = ['one', 'two', 'three' => ['a', 'b', 'c']];
        return $items;
    }
    /*
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionIndex(){
        // implement here your code
        echo 'aki'; die;
    }

    public function actionLastbuilds(){
        // implement here your code
        echo 'aki2'; die;
    }
    */

}


