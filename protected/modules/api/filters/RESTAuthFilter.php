<?php

class RESTAuthFilter extends CFilter {

    protected function preFilter($filterChain) {
        $session = !isset($_SERVER['HTTP_X_SESSION']) ? null : $_SERVER['HTTP_X_SESSION'];
        
        if ($session===null) 
             Yii::app()->rest->response->send(500, $filterChain->controller->wrappError('Brak nagłówka ID sesji.'));
        
        if (!Yii::app()->session->getIsInitialized() || Yii::app()->session->getSessionID()!=$session) {
            Yii::app()->session->close();
            Yii::app()->session->setSessionID($session);
            Yii::app()->session->open(); 
        }
        
        if (Yii::app()->user->getIsGuest()) {
            Yii::app()->rest->response->send(401, $filterChain->controller->wrappError('Użytkownik nie jest zalogowany.'));
            return false;
        }
        
        return true;
 
    }

}
