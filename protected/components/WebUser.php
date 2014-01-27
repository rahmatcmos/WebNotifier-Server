<?php
class WebUser extends CWebUser {
    //private $_keyPrefix;
    private $_access=array();

    public function checkAccess($operation,$params=array(),$allowCaching=true) {
        if (constant('YII_DEBUG') === true)
            if (AuthItem::model()->count('name = :name',array(':name'=>$operation)) == 0)
                throw new CException('Jednosta autoryzacyjna '.$operation.' nie istnieje!');

        if($allowCaching && $params===array() && isset($this->_access[$operation]))
            return $this->_access[$operation];
        else
            return $this->_access[$operation]=Yii::app()->getAuthManager()->checkAccess($operation,$this->getId(),$params);
    }
}
