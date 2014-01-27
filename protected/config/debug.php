<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'), 
    array(
        'modules' => array(
            'gii'=>array(
                'class'=>'system.gii.GiiModule',
                'password'=>'psikuta',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters'=>array('127.0.0.1','::1'),
                'generatorPaths'=>array(
                    'bootstrap.gii', // since 0.9.1
                ),
            ),
        ),        
        'components'=>array(
            'db'=>array(
                'enableParamLogging' => true, // ustawienie logowania parametrow powiazanych z zapytaniem
                'schemaCachingDuration'=>0,
            ),
            'log' => array(
                'routes' => array(
                    array(
                        'class'=>'CWebLogRoute',
                    )
                ),
            ), 
            /*'errorHandler' => array(
                'ajaxRender'=>true,
            ),*/            
        ),
    )
);