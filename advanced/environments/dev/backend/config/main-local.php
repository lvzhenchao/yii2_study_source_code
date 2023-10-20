<?php
//类的映射表，添加第三方类
Yii::$classMap['Util'] = '@backend/third/Util.php';

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'vA-uleAuaB3Bo_BIn5AeajOMWuvx-6-u',
        ],
//        'view' => [
//            'theme' => [
//                'basePath' => '@backend/web/duanwu',//指定包含主题资源（CSS, JS, images, 等等）的基准目录
//                'pathMap' => [
////                    '@backend/template' => '@backend/template/duanwu',
//                    '@backend/template' => [
//                        '@backend/template/chunjie',
//                        '@backend/template/duanwu',
//                    ],
//                ],
//            ],
//            'defaultExtension' => 'html'//默认的视图扩展名
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                "<controller:\w+>/<action:\w+>/<id:\d+>" => "<controller>/<action>",
                "<controller:\w+>/<action:\w+>"          => "<controller>/<action>",

//                '/'=>'/',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.33.10;dbname=yii', // MySQL, MariaDB
            'username' => 'root', //数据库用户名
            'password' => 'BspKCZLRZWeHeaTR', //数据库密码
            'charset' => 'utf8',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],

        //更改错误事务处理
        'errorHandler' => [
            'errorAction' => 'test/index17'
        ],

        //自定义组件
        'sms' => [
            'class' => 'backend\components\sms',
        ],
    ],
    'modules' => [
        'shop' => [
            'class' => 'backend\modules\shop\Module',
        ],
    ],
    'defaultRoute' => 'test',
    'viewPath'=> '@backend/template',
//    'layout' => 'common',
    'language' => 'zh-CN',

];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*.*.*.*'],
    ];
}

return $config;
