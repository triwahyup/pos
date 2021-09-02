<?php
use \yii\web\Request;

$baseURL = preg_replace('/web$/', '', (new Request)->getBaseUrl());;

$rules = require __DIR__ . '/rules.php';
$params = require __DIR__ . '/params.php';

if(getenv('APP_ENV') == 'local')
    $directory = 'local';
else if(getenv('APP_ENV') == 'beta')
	$directory = 'beta';
else if(getenv('APP_ENV') == 'production')
    $directory = 'production';
else
    $directory = 'local';
$db = require __DIR__ .'/'.$directory. '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'POS Point of Sales',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'N3dHi6Jkcp3Xsk71ZM20hcp-vTKLIMwL',
            'enableCsrfValidation' => false,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            
            'useFileTransport' => true,
            // 'transport' => [
            //     'class' => 'Swift_SmtpTransport',
                // 'host' => 'abs@alfanthariq.com',
                // 'username' => 'abs@alfanthariq.com',
                // 'password' => ')hzoW&K*r~[*',
            //     'port' => '587',
            //     'encryption' => 'tls',
            // ],
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
        'db' => $db,
        // 'urlManager' => [
        //     'enablePrettyUrl' => true,
        //     'enableStrictParsing' => true,
        //     'showScriptName' => false,
        //     'rules' => $rules,
        // ],
    ],
    'params' => $params,
    'modules' => [
        'master' => [
            'class' => 'app\modules\master\Module',
        ],
        'pengaturan' => [
            'class' => 'app\modules\pengaturan\Module',
        ],
        'report' => [
            'class' => 'app\modules\report\Module',
        ],
        'transaksi' => [
            'class' => 'app\modules\transaksi\Module',
        ],
    ],
];

// if (getenv('APP_DEBUG')) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
// }
return $config;