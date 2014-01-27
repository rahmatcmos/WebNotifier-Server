<?php
class RESTResponse {
    private function __construct() {}
    
    public static function factory($foramt) { 
        $className = 'RESTResponse'.  strtoupper($foramt);

        //if (!class_exists($source,false)) 
          //  throw new CException(Yii::t('rest','Typ odpowiedzi {type} nie jest obsługiwany.',array('{type}'=>$type)));
        
        return new $className;
    }
}
