<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$config = (YII_DEBUG) ? 'debug' : 'main';
require_once($yii);
Yii::$classMap=array(
    'CValidator' => dirname(__FILE__).'/protected/validators/CValidator.php'
);
Yii::createWebApplication(dirname(__FILE__).'/protected/config/'.$config.'.php')->run();