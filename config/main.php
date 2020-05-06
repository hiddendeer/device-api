<?php

return [
    'language' => 'zh-CN',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'chenshuaiyuan',
            "enableCsrfValidation" => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,//请求方式严格模式开启
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'sign-in',
                    'extraPatterns' => [
                        'POST login' => 'login',
                    ],
                    'pluralize' => false
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'sign-up',
                    'extraPatterns' => [
                        'POST register' => 'register',
                    ],
                    'pluralize' => false
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'topic',
                    'extraPatterns' => [
                        'GET info' => 'info',
                    ],
                    'pluralize' => false
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'device-record',
                    'extraPatterns' => [
                        'POST create-record' => 'create-record',
                        'GET record-index' => 'record-index',
                        'GET record-view' => 'record-view',
                    ],
                    'pluralize' => false
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'info',
                    'extraPatterns' => [
                        'GET user-info' => 'user-info',
                        'GET my-record-index' => 'my-record-index',
                    ],
                    'pluralize' => false
                ],

            ],

        ],
    ]
];