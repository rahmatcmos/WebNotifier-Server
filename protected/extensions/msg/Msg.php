<?php
class Msg {
    public static $namespaces = array('success', 'info', 'warning', 'error', 'danger');
    protected static $_messages = array();

    protected static function _checkNamespace($namespace) {
        if (!in_array($namespace, self::$namespaces))
            throw new CException(Yii::t('msg', 'Invalid namespace.'));
        return true;
    }

    protected static function _getFlash($namespace) {
        self::_checkNamespace($namespace);        
    
        if (Yii::app()->user->hasFlash($namespace))
            return Yii::app()->user->getFlash($namespace);
        return array();
    }
    
    public static function get($namespace,$includeFlash=false) {
        self::_checkNamespace($namespace);
        
        $messages = array_key_exists($namespace, self::$_messages) ? self::$_messages[$namespace] : array();
        
        return $includeFlash ? array_merge($messages,self::_getFlash($namespace)) : $messages;
    }

    public static function add($namespace, $message, $flash=false) {
        $messages = ($flash) ? self::_getFlash($namespace) : self::get($namespace,false);
        $messages[] = $message;

        if ($flash)
            Yii::app()->user->setFlash($namespace,$messages);
        else
            self::$_messages[$namespace] = $messages;
        
        return true;
    }
}

?>