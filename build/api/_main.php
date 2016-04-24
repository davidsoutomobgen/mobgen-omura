<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'api\modules\v1\models\ApiUser',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'log' => [
//            'traceLevel' => YII_DEBUG ? 3 : 0,
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['api'],
                    'except' => ['yii\web\HttpException'],
                    'logFile' => '@app/runtime/logs/api.log',
                    'logVars' => [],    // To get rid of the _SERVER info in the log
//                  'maxFileSize' => 1024 * 2,
//                  'maxLogFiles' => 20,
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'v1/whoiswho' => 'v1/whoiswho/index',
//              'v1/whoiswho/<action:\w+>' => 'v1/whoiswho/<action>',
                // http://chris-backhouse.com/Yii2-using-hyphenated-URLs-in-URL-manager-rules/1030
                'v1/whoiswho/<action:\w+(-\w+)*>' => 'v1/whoiswho/<action>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/country',
                        'v1/builds',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{apikey}' => '<apikey:\\w+>',
                        '{projectid}' => '<projectid:\\w+>',
                        '{apikey2}' => '<apikey2:\\w+>',
                        '{apikeybuild}' => '<apikeybuild:\\w+>',
                    ],
                    'extraPatterns' => [
                        'GET lastbuilds/{apikey}' => 'lastbuilds',
                        'POST registernewbuild/{projectid}/{apikey2}/{apikeybuild}' => 'registernewbuild',
                        'GET test' => 'test',
                    ],
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ]
    ],
    'params' => $params,
];



