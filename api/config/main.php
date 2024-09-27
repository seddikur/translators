<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'language' => 'ru_RU',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'urlManager' => [
//            'baseUrl' => '/api',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => 'v1/sign-up',
//                    'pluralize' => false,
//                    'extraPatterns' => [
//                        'GET' => 'index',
//                        'POST' => 'index',
//                    ],
//                    'tokens' => [
//                        '{phone}' => '<phone:\\d+>',
//                        '{code}' => '<code:\\w+>',
//                    ],
//                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'v1/projects',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET <action>' => '<action>',
                        'POST <action:update-state>' => '<action>',
                    ],
//                    'tokens' => [
//                        '{id}' => '<id:\\d+>',
//                        '{driver_id}' => '<driver_id:\\d+>',
//                        '{project_id}' => '<project_id:\\d+>',
//                        '{state_id}' => '<state_id:\\d+>',
//                        '{token}' => '<token:\\w+>',
//                    ],
                ],
            ],        
        ]
    ],
    'params' => $params,
];



