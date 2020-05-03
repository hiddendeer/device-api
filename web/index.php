<?php
use yii\helpers\VarDumper;
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

//$config = require __DIR__ . '/../config/web.php';

$config = yii\helpers\ArrayHelper::merge(require(__DIR__ . '/../config/main.php'),require(__DIR__ . '/../config/web.php'));

//打印数组并且停止执行
function halt($value){
    print_r($value);
    exit;
}

function dump($array){
    VarDumper::dump($array);
    exit;
}
(new yii\web\Application($config))->run();
