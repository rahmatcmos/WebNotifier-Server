<?php

class REST extends CApplicationComponent {
    private $_response;
    private $_format = 'JSON';
    private $_formatVar = 'format';
    
    public function init() {
        $this->initFormat();     
    }
    
    public function initFormat() {
        $format = Yii::app()->request->getQuery($this->_formatVar, null);        
        if ($format!==null)
            $this->_format = $format;
    }
    
    public function setResponse($format) {
        $this->_response = RESTResponse::factory($format);             
    }
    
    public function getResponse() {
        if ($this->_response===null) 
            $this->setResponse($this->_format);
        
        return $this->_response;
    }
}