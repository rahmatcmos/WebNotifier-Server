<?php
class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticate() {
        $record=User::model()->findByAttributes(array('email'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->password!==$this->password)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id=$record->getPrimaryKey();
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId() {
        return $this->_id;
    }
}