<?php

class ProfileController extends RESTController {

    public function actionLogin() {
        $email = Yii::app()->request->getPost('email');
        $password = Yii::app()->request->getPost('password');
       
        if ($email===null)
             Yii::app()->rest->response->send(500,$this->wrappError('Brak parametru "email".'));
        
        if ($password===null)
             Yii::app()->rest->response->send(500,$this->wrappError('Brak parametru "password".'));
        
        $identity = new UserIdentity($email, $password);
        if ($identity->authenticate()) {
            Yii::app()->user->login($identity);
            Yii::app()->rest->response->send(200, array('session' => Yii::app()->getSession()->getSessionID()));
        } else {
            switch ($identity->errorCode) {
                case UserIdentity::ERROR_USERNAME_INVALID:
                    Yii::app()->rest->response->send(401,$this->wrappError('Wskazany adres email nie istnieje w systemie.','email'));
                    break;

                case UserIdentity::ERROR_PASSWORD_INVALID:
                    Yii::app()->rest->response->send(401,$this->wrappError('Nieprawidłowe hasło.','password'));
                    break;

                case UserIdentity::ERROR_ACCOUNT_INACTIVE:
                    Yii::app()->rest->response->send(401,$this->wrappError('Konto nie jest aktywne.'));
                    break;
            }
        }
    }

}