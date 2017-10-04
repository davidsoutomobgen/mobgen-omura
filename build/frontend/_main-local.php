<?php
$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XHlWtIXgOt1OGfGeejDjaT86w3gxfT3h',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                'project/<hash:\w+>/<safename:[-\w]+>' => 'project/index',
                'build/download/<id:\w+>' => 'build/download',
                'build/<hash:\w+>/<safename:[-\w]+>' => 'build/index',
                '<controller:\w+>' => '<controller>/index',
            ),
        ],
    ],

];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
