<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
/*
//$eauthServices = array_keys($config['components']['eauth']['services']);
array_unshift($config['components']['urlManager']['rules'], array(
    'route' => 'site/login',
    //'pattern' => 'login/<service:('.implode('|', $eauthServices).')>',
));
*/

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'devicedetect'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],

        // 'settings' => [
        //     'class' => 'backend\modules\settings\Settings',
        // ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable index.php
            'showScriptName' => false,
            'enableStrictParsing' => true,
            // Disable r= routes
            'enablePrettyUrl' => true,
            'rules' => array(
                'gii' => 'gii',
                //'login/<service:google>' => 'site/login',
                'build/download/<id:\w+>' => 'build/download',
                'build/<hash:\w+>/<safename:[-\w]+>' => 'build/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<url:\w+>/<id:\d+>' => '<controller>/<action>',
                //'<url:\w+>' => 'objects/link/',
                //'<controller:\w+>/<action:\w+>/<date:\w+>' => '<controller>/<action>',
                '<controller:\w+>' => '<controller>/index',
                '' => 'site/index'
            ),
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            /*
            //Setup this to send emails with smtp and confirm permission to send emails in gmaill
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'xyz@mobgen.com',
                'password' => 'XXXXX',
                'port' => 587,
                'encryption' => 'tls',
            ],
            */
        ],
        'authManager'=>
            [
                'class' => 'yii\rbac\DbManager',
                'defaultRoles' => ['guest'],
            ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOpenId'
                ],
            ],
        ],
        /*'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '532102976967-6ugjtbbmim2o2o31v26fjolvv73pkdt1.apps.googleusercontent.com',
                    'clientSecret' => 'KgfrKwLs80wNsLYYoJbdmC8Y',
                    'title' => 'Google',
                ],

            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/eauth.log',
                    'categories' => ['nodge\eauth\*'],
                    'logVars' => [],
                ],
            ],
        ],
        */
        /*
        'MyComponent'=>[
            'class'=>'backend\components\MyComponent',
        ],
        */
    ],
    'params' => $params,
];
