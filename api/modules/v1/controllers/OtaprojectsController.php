<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;



/**
 * Builds Controller API
 *
 * @author David Souto <david.souto@mobgen.com>
 */
class OtaprojectsController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Otaprojects';
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

}


