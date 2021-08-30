<?php

// comment out the following two lines when deployed to production
require __DIR__ . '/../vendor/autoload.php';
$env = __DIR__ . '/../';

(new Dotenv\Dotenv($env))->load();

if(getenv('APP_ENV') !== 'production') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();