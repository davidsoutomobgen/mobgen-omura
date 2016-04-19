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
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
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
                ]
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



