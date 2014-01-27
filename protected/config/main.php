<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('logs', 'protected/runtime/logs');
$params = array('cookiePrefix' => 'wn_');

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Web Notifier',
    'language' => 'pl',
    'params' => $params,
    'defaultController' => 'site',
    // preloading 'log' component
    'preload' => array(
        'log',
        'bootstrap',
    ),
    // autoloading model and component classes
    'import' => array(
        'application.helpers.*',
        'application.models.*',
        'application.components.*',
        'ext.rest.*',
        'ext.msg.*',               
    ),
    'modules' => array(
        'api'
    ),
    // application components
    'components' => array(
        'format' => array(
            'dateFormat' => 'Y-m-d',
            'datetimeFormat' => 'Y-m-d H:i',
        ),
        'user' => array(
            'class' => 'WebUser',
            'loginUrl' => array('/profile/login'),
            'allowAutoLogin'=>true,            
        ),
        'session' => array(
            'sessionName' => $params['cookiePrefix'] . 'session',
            'cookieMode'=>'only',
        ),
        'db' => array(
            'class' => 'CDbConnection',
            'charset' => 'UTF8',
            'connectionString' => 'mysql:host=127.0.0.1;dbname=gilek_wn',
            'username' => 'gilek_wn',
            'password' => '',
            'schemaCachingDuration' => 3600,
        ),
        'rest' => array(
            'class' => 'REST'
        ),
        /*'errorHandler' => array(
          'class' => 'ErrorHandler',
          'ajaxRender' => false,
          ), */
        'urlManager' => array(
            'showScriptName' => false,
            'urlFormat' => 'path',
            'rules' => array(
                // REST
                array('api/<controller>/check', 'pattern' => 'api/<controller:\w+>/check.<format:\w+>', 'verb' => 'GET'),
                array('api/<controller>/login', 'pattern' => 'api/<controller:\w+>/login.<format:\w+>', 'verb' => 'POST'),
                // WWW
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'request' => array(
            'enableCookieValidation' => true,
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'auth_items',
            'assignmentTable' => 'auth_assignments',
            'itemChildTable' => 'auth_items_children',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error',
                    'logFile' => date('Y-m-d', time()) . '.txt',
                    'logPath' => Yii::getPathOfAlias('logs'),
                ),
            ),
        ),
        'bootstrap'=>array(
            'class'=>'ext.bootstrap.components.Bootstrap',
            'coreCss'=>false,
        ),        
    ),
);