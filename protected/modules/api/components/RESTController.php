<?php

class RESTController extends CController {
    public function wrappError($message,$target=null) {
        $result = array(
            'message'=>$message
        );
        if ($target!==null)
            $result['target']=$target;
        
        return array('error'=>array($result));
    }
}