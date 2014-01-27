<?php

class SessionFilter extends CFilter {

    protected function preFilter($filterChain) {
        $session = Yii::app()->request->getQuery('session');
        
        if ($session!==null) {
            Yii::app()->session->close();
            Yii::app()->session->setSessionID($session);
            Yii::app()->session->open(); 
            $cookieName = Yii::app()->session->getSessionName();
            //TODO
            //Yii::app()->request->cookies[$cookieName] = new CHttpCookie($cookieName, "psikuta");
            setCookie($cookieName,$session,0,'/');
        }
                
        return true;
 
    }

}
